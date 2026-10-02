# Data objects

Bodies can be typed in both directions without changing what reaches Cin7. A write
takes either an `array` (sent verbatim) or a `Hypervel\Data\Data` object (sent as its
`toArray()`), and a request with a response body turns it into a data object through
Saloon's `dto()`. Arrays keep working: every resource method still returns the Saloon
`Response`, and `json()` is unchanged.

```php
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;

$cin7->ref()->tax()->post(TaxData::from(['Name' => 'VAT', 'Account' => '800']));
$cin7->ref()->tax()->post(['Name' => 'VAT', 'Account' => '800']); // same body

$saved = $cin7->ref()->tax()->put($tax)->dto();       // TaxData
$rules = $cin7->ref()->tax()->get()->dto();           // list<TaxData>
$raw = $saved->getResponse()->json();                 // the untouched body
```

## Conventions

- **One `final` class per V2 model**, extending `Hypervel\Data\Data`, under `src/Data/`.
  Folders mirror the V2 path, as in `src/Requests/` (`src/Data/Ref/Tax/`). The class
  name is the reference's model name without "Model", plus `Data`: Tax Component Model
  is `TaxComponentData`. A nested model lives beside its parent; one that several
  paths use lives in `src/Data/` itself.
- **One class per model name.** Where the reference documents one name twice with
  different fields, the class carries the union. A request body gets its own class
  only where the reference documents one.
- **Property names are the wire keys, verbatim** (`ID`, `TaxRuleList`), with no name
  mapper, so `toArray()` is the JSON Cin7 expects.
- **Every property is `<type>|Optional`**, and nullable where the reference or an
  example shows `null`. A key the caller did not set is left out of `toArray()`.
- **Types follow the reference's tables:** `Guid`, `String`, `Date` and `DateTime` are
  `string`; `Decimal` is `float`; `Int` is `int`; `Bool` is `bool`; a `[] … Model` is a
  `list` of that class. There are no casts, so a date read from a response can be sent
  back unchanged.
- **Response data classes implement `WithResponse`** with `HasResponse`, so
  `getResponse()` still reaches the raw body.

## The empty-collection rule

On some endpoints an empty collection in a PUT deletes the existing records (the
reference says so for `sale/invoice`). Because every property admits `Optional`, a
collection the caller did not set is left out of the body rather than sent as `[]`.
Send `[]` only to delete on purpose, by setting the property to an empty list.

## Classes by path

| Path | Request body (POST/PUT) | `dto()` |
|---|---|---|
| `ref/tax` | `TaxData` (Tax, with `Components`: `TaxComponentData`, Tax Component Model) | GET: `list<TaxData>`; POST, PUT: `TaxData`, the saved rule (`TaxRuleList.0`) |
| `ref/customer/credits` | none | GET: `list<CustomerCreditData>` (Customer Credits) |
| `sale` | `SalePostPutData` (Sale POST/PUT Attributes, with `BillingAddress`: `AddressData`, `ShippingAddress`: `SaleShippingAddressData`, `AdditionalAttributes`: `AdditionalAttributeData`) | GET, POST, PUT, DELETE: `SaleData` (Sale) |
| `saleList` | none | GET: `list<SaleListData>` (Sale List) |

`SaleData` nests one class per model, all under `src/Data/Sale/` beside it and reused by the
later sale paths:

| Key | Class (reference model) |
|---|---|
| `BillingAddress`, `ShippingAddress`, `AdditionalAttributes` | `AddressData`, `SaleShippingAddressData`, `AdditionalAttributeData` |
| `Quote` | `SaleQuoteData`, with `Prepayments` (`SalePaymentLineData`), `Lines` (`SaleQuoteLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) |
| `Order` | `SaleOrderData`, with `Lines` (`SaleOrderLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) |
| `Fulfilments` | `SaleFulfilmentData`: `Pick` and `Pack` are `SaleFulfilmentPickPackData` (`Lines`: `SaleFulfilmentPickPackLineData`), `Ship` is `SaleFulfilmentShipData` (`Lines`: `SaleFulfilmentShipLineData`) |
| `Invoices` | `SaleInvoiceData`, with `Lines` (`SaleInvoiceLineData`), `AdditionalCharges` (`SaleInvoiceAdditionalChargeData`) and `Payments` (`SalePaymentLineData`) |
| `CreditNotes` | `SaleCreditNoteData`, with the same lines plus `Refunds` (`SalePaymentLineData`) and `Restock` (`SaleFulfilmentPickPackLineData`) |
| `ManualJournals` | `SaleManualJournalData`, with `Lines` (`SaleManualJournalLineData`) |
| `Attachments` | `AttachmentLineData`, in `src/Data/` because several paths use it |
| `InventoryMovements`, `Transactions` | `InventoryMovementLineData`, `SaleTransactionLineData` |

## Typed pages

`paginate()` yields each page's `Response`, so `->dto()` types a page. `items()`,
`collect()` and `pool()` keep yielding arrays.

```php
foreach ($cin7->ref()->tax()->paginate() as $response) {
    foreach ($response->dto() as $tax) {
        // $tax is a TaxData
    }
}
```

## Where the reference's tables and examples disagree

- **Tax Component `Percent` and `ComponentOrder`.** The table types them `Decimal` and
  `Int`, but the examples send `"10.0000000000"` and `"1"`. `TaxComponentData` accepts
  both (`float|string`, `int|string`). The table also spells the first key
  `Percent ` with a trailing space; the examples' `Percent` is the wire key.
- **Tax Component `ID` and `Compound`.** Both appear only in the examples; the first
  example component has no `Compound`. `Compound` is `string|int` because the examples
  send `"0"` and `"1"`.
- **Sale Order.** The reference documents the model twice: under "Other Models" and in
  `sale/order`'s own table (which adds `SaleID` and `CombineAdditionalCharges`).
  `SaleOrderData` carries the union. Its `Lines` are `SaleOrderLineData`, a superset of the
  Sale Quote Line the first table names: it adds `BackorderQuantity` and `DropShip`, and the
  examples also send `Backorder`.
- **Sale POST/PUT.** The POST example sends `AutoPickPackShipMode`, which no Sale table
  lists; it is modelled on `SalePostPutData`. The example also sends `"SkipQuote": "false"`,
  `"TaxInclusive": "false"` and `"CurrencyRate": "1"` as strings; the properties are `bool`
  and `float`, following the tables.
- **Product fields on lines.** "All objects that contain `ProductID` also contain additional
  fields": `ProductLength`, `ProductWidth`, `ProductHeight`, `ProductWeight`, `WeightUnits`,
  `DimensionsUnits` and `ProductCustomField1`–`10`. Every class with a `ProductID` models
  them, and the custom fields admit `null`, as the examples send.
- **Nulls.** `ExternalID`, `SourceChannel`, `Ship.RequireBy` and the invoice, due and ship
  dates and numbers of a Sale List row are `null` in the examples, so those properties admit
  `null`.
- **Credit note `Restock`.** The reference types it as Sale Fulfilment Pick Pack Line, whose
  table includes the `Restock…` keys and `Box`; the example's restock line carries only the
  pick keys, and the others stay unset.
- **Examples that are not valid JSON** are fixed when they become a fixture in
  `Cin7Payloads`, not copied verbatim.
