# Resources

Every Cin7 call goes through a resource, reached from `Cin7Connector` by an
accessor that spells the V2 path: `$cin7->customer()`. A resource is a thin,
stateless wrapper around the request classes described in
[requests](requests.md); it builds the request and sends it, or hands it to
`paginate()` for a list. The accessor chain, not a generic client call, is the
public surface of this package.

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->customer()->get()->json();

// POST customer, body {"Name":"ACME"}
$new = $this->cin7->customer()->post(['Name' => 'ACME'])->json();

// PUT customer, body {"ID":"…","Name":"ACME Ltd"}
$this->cin7->customer()->put(['ID' => $guid, 'Name' => 'ACME Ltd']);

// Every customer, across all pages
foreach ($this->cin7->customer()->paginate()->items() as $customer) {
    // $customer is one entry of CustomerList
}
```

## Conventions

- **Folders mirror the V2 path.** `src/Requests/<Path>` and
  `src/Resources/<Path>` follow the Cin7 V2 path below `ExternalApi/v2/`, one
  StudlyCase subfolder per segment: `sale/invoice` becomes `Sale/Invoice/`.
- **The accessor chain spells the path.** `$cin7->customer()`,
  `$cin7->sale()->invoice()`.
- **Methods are HTTP verbs.** `get()`, `post()`, `put()`, `delete()`, plus
  `paginate()` on list endpoints. A keyed `get()` or `delete()` takes the
  identifier as its first argument, e.g. `get(string $id, array $parameters =
  [])`.
- **A write's identifier is the caller's job.** `post()` and `put()` take the
  body verbatim; the caller merges in the identifier a PUT needs (see
  [PUT identifiers](#put-identifiers)).
- **Resources are stateless.** `BaseResource` holds only the connector, and
  every accessor on `Cin7Connector` returns a fresh instance, so the resource
  never becomes state shared across coroutines. See
  [requests](requests.md) for the request classes a resource builds.

## The accessor tree

| Accessor | Resource | Methods |
|---|---|---|
| `$cin7->customer()` | `CustomerResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator`, `post(array $body)`, `put(array $body)` |

The other nine accessors in the V2 reference (`product`, `sale` and its
sub-resources, `ref/tax`, `ref/customer/credits`, `moneyOperation`) land in the
rest of this series; see the tracking issue for the plan.

## Customer

`customer` has no GUID-keyed find: a filter such as `['ID' => $guid]` goes
through `get()` like any other filter, because V2 answers `customer?ID=…` with
the same `{Total, Page, CustomerList}` envelope as an unfiltered list.

```php
$match = $this->cin7->customer()->get(['ID' => $guid])->json('CustomerList')[0] ?? null;
```

## PUT identifiers

A PUT body carries the identifier V2 documents for that resource. The caller
assigns it last, so it wins over anything already in the array under the same
key:

| Resource | PUT body carries |
|---|---|
| `customer` | `ID` |

```php
$attributes['ID'] = $guid;

$this->cin7->customer()->put($attributes);
```

## Adding an action

A new resource method is a thin wrapper, never a reimplementation of a
request:

1. Add the request class under `src/Requests/<Path>/`, extending
   `ListRequest`, `KeyedRequest` or `WriteRequest` as the action calls for (see
   [writing a request class](requests.md#writing-a-request-class)).
2. Add the method to the resource, delegating to `$this->connector->send()` or
   `$this->connector->paginate()`.
3. Add a row to
   [`RequestCatalogueTest`](../tests/Feature/Requests/RequestCatalogueTest.php)
   and to
   [`ResourceCatalogueTest`](../tests/Feature/Resources/ResourceCatalogueTest.php).
4. Add the accessor, and its test in
   [`ConnectorResourcesTest`](../tests/Feature/Resources/ConnectorResourcesTest.php),
   only when it is new.
5. Document the resource here and the request in [requests](requests.md).
