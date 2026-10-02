# Data objects

Bodies can be typed in both directions. A write takes either an `array` (sent verbatim) or a
`Hypervel\Data\Data` object (sent as its `toArray()` without nulls, once it passes its
rules; see [write bodies](#write-bodies)), and a request with a response body turns it into a
data object through Saloon's `dto()`. Arrays keep working: every resource method still returns
the Saloon `Response`, and `json()` is unchanged.

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
  only where the reference documents one, or where the verbs need different fields.
- **Models that share fields extend an abstract parent.** The shared fields are declared
  once, as properties, in an `Abstract…Data` class, and each model is a final child that
  adds its own; a field one model requires stays in that model's constructor, since PHP
  does not let a child make an inherited field required. A field declared in a parent is
  set through `from()`, like any other.

  | Parent | Models |
  |---|---|
  | `AbstractSaleData` | `SaleData`, `SalePostPutData` |
  | `AbstractSaleInvoiceData` | `SaleInvoiceData`, `SaleInvoicePartialData`, `SaleInvoicePostData`, `SaleInvoicePutData` |
  | `AbstractSaleCreditNoteData` | `SaleCreditNoteData`, `SaleCreditNotePartialData`, `SaleCreditNotePostData` |
  | `AbstractSaleLineData` | `SaleQuoteLineData`, `SaleOrderLineData`, `SaleInvoiceLineData` |
  | `AbstractSaleChargeData` | `SaleAdditionalChargeData`, `SaleInvoiceAdditionalChargeData` |
  | `AbstractSalePaymentLineData` | `SalePaymentLineData`, `SaleCreditNotePaymentData` |
  | `AbstractAddressData` | `AddressData`, `SaleShippingAddressData` |

  Two field sets several unrelated models carry are traits in `src/Data/Concerns/`:
  `HasProductFields` (the product fields of every line with a `ProductID`) and
  `HasAdditionalAttributes` (`AdditionalAttribute1` to `10`).
- **Property names are the wire keys, verbatim** (`ID`, `TaxRuleList`), with no name
  mapper, so `toArray()` is the JSON Cin7 expects.
- **A field the reference requires has no default.** It is not nullable, and the model
  cannot be built without it: `from()` throws a `Hypervel\Data\Exceptions\CannotCreateData`.
  Required fields come first in the constructor. Where the reference requires different
  fields per verb, the body is a class per verb (see
  [sale invoices, credit notes and payments](#sale-invoices-credit-notes-and-payments)).
- **Every other field is `?type = null`.** A field the caller did not set is `null`, and
  `null` means skipped: a write leaves it out of the body. A response's `toArray()` has a
  key for every field, `null` where the response had none or sent `null`.
- **Types follow the reference's tables:** `Guid`, `String`, `Date` and `DateTime` are
  `string`; `Decimal` is `float`; `Int` is `int`; `Bool` is `bool`; a `[] … Model` is a
  `list` of that class. There are no casts, so a date read from a response can be sent
  back unchanged.
- **String fields carry the reference's rules** as validation attributes: `#[Max(n)]` for
  its Length column, `#[Uuid]` for a `Guid`, `#[DateTime]`
  (`Ipsocode\Cin7\Data\Attributes\DateTime`) for a `DateTime`, and `#[Date]` for a `Date`.
  They are checked when the model is sent as a write body, never when a response is read,
  so a response Cin7 sends outside them still becomes a data object.
- **Response data classes implement `WithResponse`** with `HasResponse`, so
  `getResponse()` still reaches the raw body.

## The empty-collection rule

On some endpoints an empty collection in a PUT deletes the existing records (the
reference says so for `sale/invoice`). A collection the caller did not set is `null`, so it
is left out of the body rather than sent as `[]`. Send `[]` only to delete on purpose, by
setting the property to an empty list; only `null` is removed from a body, never an empty
list, an empty string, `0` or `false`.

## Write bodies

A data object becomes a write body in four steps:

1. **Mandatory.** Its constructor has already demanded the fields the reference requires.
2. **Optional.** Every other field the caller did not set is `null`.
3. **Removed if empty.** `toArray()` is taken without its `null` values, at every depth (a
   list keeps its items), and the request's [`$omit`](requests.md#fields-left-out-of-write-bodies)
   paths are removed.
4. **Validated.** What remains is checked against the class's rules: the types and required
   fields Hypervel Data reads from the constructor, and the reference's lengths, GUIDs and
   dates from the attributes. A failure throws a `Hypervel\Validation\ValidationException`
   from `send()` before anything goes out, naming each field, nested ones as
   `Lines.0.SKU`.

The rules run through Hypervel Data's own validation, the `validate()` every data class
has, so there is no validation code per class.

An array body skips all four: it is sent as given, less the `$omit` paths. That is the way to
send an explicit `null`, to clear a field:

```php
$this->cin7->customer()->put(['ID' => $guid, 'TaxNumber' => null]);            // TaxNumber: null
$this->cin7->customer()->put(CustomerData::from(['ID' => $guid, 'TaxNumber' => null])); // left out
```

Because a data object's `toArray()` carries every field, spreading it into an array body
sends a `null` for each field the caller left unset. To change a model before sending it,
build a data object again:

```php
$this->cin7->customer()->put(CustomerData::from([...$customer->toArray(), 'ID' => $guid]));
```

## Classes by path

| Path | Request body (POST/PUT) | `dto()` |
|---|---|---|
| `customer` | `CustomerData` (Customer, with `Addresses`: `CustomerAddressData`, `Contacts`: `CustomerContactData` and `ProductPrices`: `ProductPriceData`, Customer specific Product Price Model) | GET: `list<CustomerData>`; POST, PUT: `CustomerData`, the saved customer (`CustomerList.0`); responses add `ChildCustomers`: `ChildCustomerData` |
| `product` | `ProductData` (Product, with `Suppliers`: `ProductSupplierData` and its `ProductSupplierOptions`: `ProductSupplierOptionData` and `SupplyIntervals`: `ProductSupplierOptionIntervalData`, `ReorderLevels`: `ReorderLevelData`, `BillOfMaterialsProducts`: `BillOfMaterialProductData`, `BillOfMaterialsServices`: `BillOfMaterialServiceData`, `Movements`: `ProductMovementData`, `Attachments`: `AttachmentLineData` and `CustomPrices`: `ProductPriceData`) | GET: `list<ProductData>`; POST, PUT: `ProductData`, the saved product (`Products.0`) |
| `ref/tax` | `TaxData` (Tax, with `Components`: `TaxComponentData`, Tax Component Model) | GET: `list<TaxData>`; POST, PUT: `TaxData`, the saved rule (`TaxRuleList.0`) |
| `ref/customer/credits` | none | GET: `list<CustomerCreditData>` (Customer Credits) |
| `moneyOperation` | `MoneyTaskData` (Money Task, with `Lines`: `MoneyTaskLineData`, Money Task Line Model) | GET, POST, PUT, DELETE: `MoneyTaskData`, with `Transactions`: `TransactionStockLineData` (Transaction Stock Line Model) and `Attachments`: `AttachmentLineData` |
| `moneyTaskList` | none | GET: `list<MoneyTaskListData>` (Money Task List) |
| `sale` | `SalePostPutData` (Sale POST/PUT Attributes, with `BillingAddress`: `AddressData`, `ShippingAddress`: `SaleShippingAddressData`, `AdditionalAttributes`: `AdditionalAttributeData`) | GET, POST, PUT, DELETE: `SaleData` (Sale) |
| `saleList` | none | GET: `list<SaleListData>` (Sale List) |
| `sale/order` | `SaleOrderData` (Sale Order, plus `AutoPickPackShipMode`, which the reference documents only in prose) | GET, POST: `SaleOrderData` |
| `sale/invoice` | POST: `SaleInvoicePostData` (Sale Invoice POST Model); PUT: `SaleInvoicePutData` (its fields, needing only `SaleID` and `TaskID`) | GET, POST, PUT, DELETE: `SaleInvoicesData` (`{SaleID, Invoices}`, with `Invoices`: `SaleInvoicePartialData`, Sale Invoice Partial Model) |
| `sale/creditnote` | POST: `SaleCreditNotePostData` (Sale Credit Note POST Model) | GET, POST, DELETE: `SaleCreditNotesData` (`{SaleID, CreditNotes}`, with `CreditNotes`: `SaleCreditNotePartialData`, Sale Credit Note Invoice Partial Model, whose `Payments` are `SaleCreditNotePaymentData`) |
| `sale/payment` | POST: `SalePaymentPostData`; PUT: `SalePaymentPutData` (the Sale Payment Line Partial Model's fields for each verb) | GET: `list<SalePaymentLinePartialData>`, a bare array; POST, PUT: `SalePaymentLinePartialData`; DELETE: `{Success}`, left to `json()` |
| any | none | `ErrorData` (Error Model, `{ErrorCode, Exception}`): not a `dto()`, since an Error Model body throws; read it from the exception's response, see [errors](requests.md#errors) |

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
  `DimensionsUnits` and `ProductCustomField1`–`10`. The sale line, pick and pack line and
  inventory movement classes take them from the `HasProductFields` trait.
- **Nulls.** `ExternalID`, `SourceChannel`, `Ship.RequireBy` and the invoice, due and ship
  dates and numbers of a Sale List row are `null` in the examples, so those properties admit
  `null`.
- **Credit note `Restock`.** The reference types it as Sale Fulfilment Pick Pack Line, whose
  table includes the `Restock…` keys and `Box`; the example's restock line carries only the
  pick keys, and the others stay unset.
- **Money Task `SupplierCustomerName`.** The table names the counterparty
  `SupplierCustomerName`, but every example returns `SupplierCustomer`; `MoneyTaskData` models
  the wire key, `SupplierCustomer`.
- **Money Task Line `TaxRule` and `Account`.** The table names them so, but every example
  sends `TaxRuleName` and `AccountCode`; `MoneyTaskLineData` models the example keys.
- **Money Task nulls.** `SupplierID`, `CustomerID` and `Note` are `null` in the examples, so
  those properties admit `null`.
- **Customer `AdditionalAttribute#`.** The table lists one row, "# - int(1-10)". On the wire
  these are ten keys, `AdditionalAttribute1` to `AdditionalAttribute10`, which `CustomerData`,
  `ProductData` and `AdditionalAttributeData` take from the `HasAdditionalAttributes` trait.
- **Customer `TaxNumber`.** The table types it `Int`, but every example has `""` or `null`, so
  it is a nullable string. `Discount` and `CreditLimit` are `float`, since a decimal is
  harmless where an integer is documented.
- **Customer addresses and contacts.** The examples also send `CustomerID` on each address
  and contact, and `JobTitle` on each contact; the classes model them.
- **One class for two price models.** Product's Custom Price and Customer's Product Price are
  the same Customer specific Product Price Model, so `ProductPriceData` serves both.
- **Product `PriceTiers`.** The Price Tier Model's one row is named after the account's tier
  (`Tier 1`, or whatever the account renamed it), so it cannot be a set of properties. It is
  an `array<string, float>`, and there is no `PriceTierData`. `PriceTier1` to `PriceTier10`
  are ordinary properties.
- **Product supplier link.** The table names it `SupplierProductURL`; the POST example sends
  `URL`. `ProductSupplierData` models the table's name.
- **Product `Movements.BatchSN`.** The table types it `Decimal`, but the example has `"1"`
  and a batch or serial number is not a quantity, so it is a nullable string.
- **Payment `Type`.** The Sale Payment Line Partial table lists `PREPAYMENT`, `PAYMENT` and
  `REFUND`; every example sends `Payment` or `Refund`, and the notes write `Prepayment`. The
  classes document the examples' spelling and do not restrict the value.
- **Credit note payments.** The payments of a `sale/creditnote` GET with `IncludePaymentInfo`
  carry `SaleOrderNumber`, `InvoiceNumber`, `CreditNoteNumber`, `Type` and `CreditID` besides
  the Sale Payment Line Model's fields, and no model names them; they are
  `SaleCreditNotePaymentData`, modelled on the example.
- **Partial models and the models embedded in a Sale.** A Sale's `Invoices` carry `Payments`
  and the totals and `Paid`, and its `CreditNotes` carry `Refunds` and the totals; the Partial
  tables and every `sale/invoice` and `sale/creditnote` example have none of them, so the
  partial classes leave them out.
- **Required per verb.** The tables have one Required column for every verb. The
  `sale/invoice` PUT notes allow leaving attributes out, so `SaleInvoicePutData` requires only
  `SaleID` and `TaskID`; a payment PUT may not carry `Amount` or `Account` when it is a
  credit, so `SalePaymentPutData` requires only `ID`.
- **Payment `CreditID` on POST.** The table makes `CreditID` PUT-only, but the POST example
  sends `"CreditID": null`; `SalePaymentPostData` has no `CreditID`.
- **Auto-generated numbers.** The invoice and credit note POST tables have no `InvoiceNumber`
  or `CreditNoteNumber` (Cin7 generates them), so the POST classes leave them out.
- **Examples that are not valid JSON** are fixed when they become a fixture in
  `Cin7Payloads`, not copied verbatim.

## Sale invoices, credit notes and payments

These follow the reference's tables field for field, with a class per verb where the verbs
need different fields, and the required fields of each table without a default:

| Class | Folder | Required |
|---|---|---|
| `SaleInvoicePartialData` (response) | `src/Data/Sale/Invoice/` | `TaskID`, `CombineAdditionalCharges`, `Status`, `InvoiceDate`, `InvoiceDueDate` |
| `SaleInvoicePostData` | `src/Data/Sale/Invoice/` | `SaleID`, `TaskID`, `CombineAdditionalCharges`, `Status`, `InvoiceDate`, `InvoiceDueDate` |
| `SaleInvoicePutData` | `src/Data/Sale/Invoice/` | `SaleID`, `TaskID` |
| `SaleCreditNotePartialData` (response) | `src/Data/Sale/CreditNote/` | `TaskID`, `CombineAdditionalCharges`, `Status`, `CreditNoteDate` |
| `SaleCreditNotePostData` | `src/Data/Sale/CreditNote/` | `SaleID`, `TaskID`, `CombineAdditionalCharges`, `CreditNoteInvoiceNumber`, `Status`, `CreditNoteDate` |
| `SalePaymentLinePartialData` (response) | `src/Data/Sale/Payment/` | `ID`, `TaskID`, `Type`, `Amount`, `DatePaid`, `Account`, `CurrencyRate` |
| `SalePaymentPostData` | `src/Data/Sale/Payment/` | `TaskID`, `Type`, `Amount`, `DatePaid`, `Account`, `CurrencyRate` |
| `SalePaymentPutData` | `src/Data/Sale/Payment/` | `ID` |

They reuse the line, additional-charge and restock classes from `src/Data/Sale/`. A response
missing a required field fails `dto()` with a `CannotCreateData`.

- The credit note Partial table does not list `CreditNoteInvoiceNumber`, which every example
  carries, nor `CreditNoteBalance` and `Payments`, which `IncludePaymentInfo` adds;
  `SaleCreditNotePartialData` models them from the examples.
- On `sale/invoice` PUT, an empty collection deletes the existing records (see
  [the empty-collection rule](#the-empty-collection-rule)).
- The reference's `sale/invoice` and `sale/creditnote` examples carry trailing commas, and the
  credit note POST example an unquoted `SaleID:` key; the fixtures are the corrected JSON.
