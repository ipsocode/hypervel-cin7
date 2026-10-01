# Endpoints

[`Ipsocode\Cin7\Endpoint`](../src/Endpoint.php) is a string-backed enum with
one case per Cin7 endpoint the package knows. Each case carries everything the
[requests](requests.md) need: the path, the key a find or update sends the GUID
under, the key a delete sends it under, and the write verbs Cin7 accepts there.

## The endpoint table

| Case | Value | Path | GUID key (find/update) | Delete key | Verbs beyond GET |
|---|---|---|---|---|---|
| `Customer` | `customer` | `customer` | `ID` | `ID` | POST, PUT |
| `Product` | `product` | `product` | `ID` | `ID` | POST, PUT |
| `Sale` | `sale` | `sale` | `ID` | `ID` | POST, PUT, DELETE |
| `SaleInvoice` | `saleInvoice` | `sale/invoice` | `SaleID` | `ID` | POST, DELETE |
| `SaleOrder` | `saleOrder` | `sale/order` | `SaleID` | `ID` | POST |
| `SaleCreditNote` | `saleCreditNote` | `sale/creditnote` | `SaleID` | `ID` | POST, DELETE |
| `SalePayment` | `salePayment` | `sale/payment` | `ID` | `ID` | POST, PUT, DELETE |
| `SaleList` | `saleList` | `saleList` | `SaleID` | `ID` | none |
| `Tax` | `tax` | `ref/tax` | `ID` | `ID` | POST, PUT |
| `MoneyOperation` | `moneyOperation` | `moneyOperation` | `ID` | `ID` | POST, PUT, DELETE |
| `CustomerCredits` | `customerCredits` | `ref/customer/credits` | `CustomerID` | `ID` | none |

The columns map onto the enum's methods: `path()`, `guidKey()`,
`deleteGuidKey()` and `writeMethods()`.

## Resolving an endpoint from a string

`Endpoint::fromAccessor()` turns a string into a case, so code that holds
endpoint names as strings can pass them straight through:

```php
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\ListRecords;

$endpoint = Endpoint::fromAccessor('saleInvoice'); // Endpoint::SaleInvoice

$this->cin7->send(new ListRecords($endpoint));
```

- It matches the case **value**, not the case name, so it is case-sensitive:
  `'saleInvoice'` resolves, `'SaleInvoice'` and `'Customer'` do not.
- An unknown or empty string throws a `ValueError`. That is an `Error`, not an
  `Exception`: an unknown name is a programming mistake, unlike the
  `MethodNotAllowedException` an unsupported verb throws (see
  [requests](requests.md#errors)).

## Verbs

GET is granted to every endpoint by
[`Cin7Request`](../src/Requests/Cin7Request.php) and is never listed in
`writeMethods()`, which holds only POST, PUT and DELETE. A request whose verb is
neither GET nor in that list throws `MethodNotAllowedException` from its
constructor, before any HTTP call. Cin7 itself answers a verb an endpoint does
not support with a 403, so the gate turns that into an error raised before the
request is sent.

`SaleList` and `CustomerCredits` are read-only: their `writeMethods()` is empty,
so only `ListRecords` and `FindRecord` can be built for them.

## GUID keys

`guidKey()` is the key a find sends in its query string and an update merges
into its body. It is `ID` except on:

| Endpoint | Key |
|---|---|
| `SaleInvoice`, `SaleOrder`, `SaleCreditNote`, `SaleList` | `SaleID` |
| `CustomerCredits` | `CustomerID` |

Every endpoint that accepts PUT uses `ID`, including `Sale` and
`SalePayment`.

`deleteGuidKey()` is `ID` on every endpoint modelled here, including the
`sale/*` ones that find by `SaleID`, so a delete on `sale/invoice` sends
`DELETE sale/invoice?ID=…&page=1&limit=100`. Not every Cin7 endpoint deletes
under `ID`; see `Account` below.
`deleteGuidKey()` returns `ID` without a `match`; an endpoint that deletes under
another key turns it into one.

### `CustomerCredits`

The `CustomerID` GUID key for `CustomerCredits` (`ref/customer/credits`) has
not yet been verified against Cin7's API reference. Check it there before
relying on `FindRecord` for this endpoint.

## Adding an endpoint

Endpoints are data, so a new endpoint is a new case plus its rows in the
methods above, never a new class:

1. Add the case. Its value is the name `fromAccessor()` resolves.
2. Add its path to `path()` and its verbs to `writeMethods()`. Both matches have
   no default arm, so a case missing from either throws an
   `UnhandledMatchError` when that method is called.
3. Give it a key in `guidKey()` or `deleteGuidKey()` only when the key is not
   `ID`.
4. Add its row to the provider in
   [`tests/Unit/EndpointTest.php`](../tests/Unit/EndpointTest.php). The suite
   fails while any case has no row. A case that deletes under a key other than
   `ID` also updates `testEveryEndpointDeletesByIdRegardlessOfItsFindKey()`, and
   a read-only case updates `testTheReadOnlyEndpointsAreSaleListAndCustomerCredits()`.

Cin7 endpoints not listed above are added this way when they are needed. Two
of them need non-default keys:

- `Account` is keyed by `Code` for find, update **and** delete, so its case
  needs a `deleteGuidKey()` arm as well as a `guidKey()` one.
- `ProductMarkupPrices` finds and updates under `ProductID`.
