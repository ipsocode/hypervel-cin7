# Pagination

A [`ListRequest`](requests.md#the-three-bases) is the only paginatable
request. `Cin7Connector` implements Saloon's `HasPagination`, and its
`paginate()` returns a [`Cin7Paginator`](../src/Pagination/Cin7Paginator.php)
that reads Cin7's list envelope and works out the last page from it, so a
listing can walk every page or send them all at once through the framework's
coroutine pool. A resource's own `paginate()` method, such as
`CustomerResource::paginate()`, is the usual way to reach it; see
[resources](resources.md). The requests themselves are described in
[requests](requests.md), and the `page`/`limit` defaults every list carries
under [page defaults](requests.md#page-defaults).

## Walking every item

`items()` sends one page at a time, lazily, and yields each record from the
page's list:

```php
foreach ($this->cin7->customer()->paginate()->items() as $customer) {
    // $customer is one entry of CustomerList
}
```

Filters go in the resource method's parameters, and every page carries them:

```php
$paginator = $this->cin7->customer()->paginate(['Name' => 'ACME']);
```

## Page size

`perPageLimit()` sends `limit` (lowercase, the spelling Cin7 reads) on every
page, and takes precedence over a `limit` passed in the request's parameters:

```php
$paginator = $this->cin7->customer()->paginate()->perPageLimit(250);
```

Without `perPageLimit()`, `applyPagination()` sets only `page` and leaves
`limit` alone, so the request's own value stands: a `limit` in its parameters,
or `PageDefaults::LIMIT` (100) when there is none.

## Fetching pages concurrently

`pool()` sends the first page alone to learn the total, then every remaining
page through the framework's bounded coroutine pool, with at most `concurrency`
calls in flight. It returns one `Response` per page, keyed by page offset (`0`
is the first page):

```php
use Hypervel\Saloon\Http\Response;

/** @var array<int, Response> $responses */
$responses = $this->cin7->customer()->paginate()
    ->perPageLimit(100)
    ->pool(concurrency: 5);
```

Each pooled call still passes through the connector's rate limiter, so a pool
never outruns the account limit; past it, the calls wait for capacity. The
concurrency bounds how many of the pool's calls are in flight at once, not the
rate at which the limiter admits them. See
[connector](connector.md#rate-limiting).

## The list envelope

Cin7 wraps a list in an envelope:

```json
{
    "Total": 250,
    "Page": 1,
    "CustomerList": [{ "ID": "…", "Name": "ACME" }]
}
```

- `Total` is the full number of matching records, across all pages.
- `Page` is the page just served.
- The list itself sits under a key that differs per endpoint (`CustomerList`,
  `ProductList`, `SaleList`, …).

The paginator finds the list by its suffix: the first string key ending in
`List` whose value is an array. It does not take the first array in the body,
because an envelope can also carry arrays such as `Errors` or `Warnings`, and
those are ignored wherever they appear. An envelope with no `…List` key yields
no items.

## Finding the last page

Cin7 never says how many pages there are, and never echoes the limit back. The
paginator divides `Total` by the `limit` that went out on the wire, read from
the sent request's query string, and rounds up:

| `limit` sent | `Total` | Pages |
|---|---|---|
| 100 (the default) | 250 | 3 |
| 5 (a caller's `['limit' => 5]`, no `perPageLimit()`) | 7 | 2 |
| any | 0 or absent | 1 |

A page is the last one when the `Page` Cin7 reports is at least the page count;
if a response has no `Page`, the paginator's own page number stands in.

Reading the limit off the sent request matters: a caller can set `limit` as a
request parameter without ever calling `perPageLimit()`, and dividing by the
default of 100 in that case would undercount the pages and stop early.

## Only a `ListRequest` paginates

`ListRequest` is the only request base that implements `Paginatable`. Saloon's
paginator rejects any other request with an `InvalidArgumentException`, and no
request declares `HasRequestPagination`, so `Cin7Paginator` is the only
paginator the connector needs.

The behavior above is pinned by
[`Cin7PaginatorTest`](../tests/Feature/Pagination/Cin7PaginatorTest.php).
