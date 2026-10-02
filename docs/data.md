# Data objects

Bodies can be typed in both directions. A write takes either an `array` (sent verbatim) or a
`Hypervel\Data\Data` object (sent as its `toArray()` without nulls, once it passes its
rules; see [write bodies](#write-bodies)), and a request with a response body turns it into a
data object through Saloon's `dto()`. Arrays keep working: every resource method still returns
the Saloon `Response`, and `json()` is unchanged.

```php
use Ipsocode\Cin7\Data\Ref\Tax\TaxPostData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxPutData;

$cin7->ref()->tax()->post(TaxPostData::from(['Name' => 'VAT', 'Account' => '800', 'IsActive' => true, 'TaxInclusive' => false]));
$cin7->ref()->tax()->post(['Name' => 'VAT', 'Account' => '800', 'IsActive' => true, 'TaxInclusive' => false]); // same body

$saved = $cin7->ref()->tax()->put(TaxPutData::from([...$tax->toArray(), 'Account' => '820']))->dto(); // TaxData
$rules = $cin7->ref()->tax()->get()->dto();           // list<TaxData>
$raw = $saved->getResponse()->json();                 // the untouched body
```

## Conventions

- **One `final` class per V2 model**, extending `Hypervel\Data\Data`, under `src/Data/`.
  Folders mirror the V2 path, as in `src/Requests/` (`src/Data/Ref/Tax/`). The class
  name is the reference's model name without "Model", plus `Data`: Tax Component Model
  is `TaxComponentData`. A model lives in the folder of the path it belongs to: the path
  that returns it, or the sale path a model the Sale embeds is named for
  (`src/Data/Sale/Order/SaleOrderData.php`, the fulfilment's ship model in
  `src/Data/Sale/Fulfilment/Ship/`). A model several paths of one family share lives in
  their common folder (`SaleAdditionalChargeData` in `src/Data/Sale/`), and one shared
  across families in `src/Data/` itself. The Money Task's classes are in
  `src/Data/MoneyTask/`, like its requests (see [resources](resources.md#conventions)).
  `src/Data/` holds nothing else: the traits the models share are in `src/Concerns/` and the
  validation attribute in `src/Attributes/`.
- **One class per model name.** Where the reference documents one name twice with
  different fields, the class carries the union. A request body gets its own class
  only where the reference documents one, or where the verbs need different fields.
- **Models that share fields extend an abstract parent.** The shared fields are declared
  once, in an `Abstract…Data` class, and each model is a final child that adds its own.
  A field every one of the parent's tables requires is a parameter of the parent's
  constructor; a child with a constructor of its own takes it there and passes it on. A
  field only some of the tables require stays in those children's constructors, since PHP
  does not let a child make an inherited optional field required. The parent's optional
  fields are properties, set through `from()` like any other.

  | Parent | Models | Required in every model |
  |---|---|---|
  | `AbstractCustomerData` | `CustomerData`, `CustomerPostData`, `CustomerPutData` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule` |
  | `AbstractMoneyTaskData` | `MoneyTaskData`, `MoneyTaskPostData`, `MoneyTaskPutData` | `TaskType`, `Status`, `BankAccount`, `Date` |
  | `AbstractTaxData` | `TaxData`, `TaxPostData`, `TaxPutData` | `Name`, `Account`, `IsActive`, `TaxInclusive` |
  | `AbstractProductData` | `ProductData`, `ProductPostData`, `ProductPutData` | `SKU`, `Name`, `Category`, `CostingMethod`, `UOM`, `Status`; and `QuantityToProduce` and `AssemblyCostEstimationMethod` on a write body with a bill of materials (see [products](#products)) |
  | `AbstractSaleListData` | `SaleListData`, `SaleCreditNoteListData` | the 19 fields both list tables require but `QuoteStatus` and `CombinedTrackingNumbers` (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractSaleQuoteData` | `SaleQuoteData`, `SaleQuotePostData` | `Memo`, `Status`, `Lines` |
  | `AbstractSaleManualJournalData` | `SaleManualJournalData`, `SaleManualJournalPostData` | `Status` |
  | `AbstractSaleData` | `SaleData`, `SalePostData`, `SalePutData` | `Location`, `CurrencyRate`; and `Customer` or `CustomerID` on a write body (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractSaleInvoiceData` | `SaleInvoiceData`, `SaleInvoicePartialData`, `SaleInvoicePostData`, `SaleInvoicePutData` | `TaskID` |
  | `AbstractSaleFulfilmentPickPackTaskData` | `SaleFulfilmentPickData`, `SaleFulfilmentPickPostData`, `SaleFulfilmentPickPutData`, `SaleFulfilmentPackData`, `SaleFulfilmentPackPostData` | `TaskID` |
  | `AbstractSaleFulfilmentShipTaskData` | `SaleFulfilmentShipPostData`, `SaleFulfilmentShipPutData` | `TaskID`, `Status` (`DRAFT`, `PARTIALLY AUTHORISED` or `AUTHORISED`) |
  | `AbstractSaleCreditNoteData` | `SaleCreditNoteData`, `SaleCreditNotePartialData`, `SaleCreditNotePostData` | `TaskID`, `Status`, `CreditNoteDate` |
  | `AbstractLineData` | `SaleQuoteLineData`, `SaleOrderLineData`, `SaleInvoiceLineData`; shaped to serve the purchase line models too | `ProductID`, `SKU`, `Name`, `Quantity`, `Price`, `Tax`, `TaxRule` |
  | `AbstractChargeData` | `SaleAdditionalChargeData`, `SaleInvoiceAdditionalChargeData`; shaped to serve the purchase charge models too | `Description`, `Quantity`, `Price`, `Tax`, `TaxRule` |
  | `AbstractSalePaymentLineData` | `SalePaymentLineData`, `SaleCreditNotePaymentData` | none |
  | `AbstractAddressData` | `AddressData`, `SaleShippingAddressData` | `Line1`, `Country` |

  The line and charge requirements hold in the purchase tables as well, so a purchase model
  can extend those parents unchanged. `Account`, which the purchase invoice tables require
  and the sale invoice tables do not, belongs on the children.

  Two field sets several unrelated models carry are traits in `src/Concerns/`:
  `HasProductFields` (the product fields of every line with a `ProductID`) and
  `HasAdditionalAttributes` (`AdditionalAttribute1` to `10`).
- **Property names are the wire keys, verbatim** (`ID`, `TaxRuleList`), with no name
  mapper, so `toArray()` is the JSON Cin7 expects.
- **A required field has no default.** It is not nullable, and the model cannot be built
  without it: `from()` throws a `Hypervel\Data\Exceptions\CannotCreateData`. Required fields
  come first in the constructor. Every class requires the fields its table does (see
  [customers](#customers), [products](#products), [tax rules and money
  tasks](#tax-rules-and-money-tasks) and
  [sale invoices, credit notes and payments](#sale-invoices-credit-notes-and-payments)), as do
  the parents above; the Money Task List and Customer Credits tables require none. Where the
  reference requires different fields per verb, the body is a class per verb.
- **Every other field is `?type = null`.** A field the caller did not set is `null`, and
  `null` means skipped: a write leaves it out of the body. A response's `toArray()` has a
  key for every field, `null` where the response had none or sent `null`.
- **Types follow the reference's tables:** `Guid`, `String`, `Date` and `DateTime` are
  `string`; `Decimal` is `float`; `Int` is `int`; `Bool` is `bool`; a `[] … Model` is a
  `list` of that class. There are no date casts, so a date read from a response can be sent
  back unchanged.
- **A closed list of values is an enum.** A field whose values the reference lists, in a
  named list (Sale Statuses, Order Statuses, …) or in its notes ("Possible values are …"),
  is typed with a string-backed enum from `src/Enums/`: `?SaleStatus $Status = null`, or
  `TaskStatus $Status` when required. Case names are PascalCase and the values are the wire
  strings verbatim (`TaskStatus::NotAvailable` is `'NOT AVAILABLE'`), so `toArray()` sends
  what Cin7 expects. Fields with the same list share one enum, and a field whose list is a
  subset of another's uses the larger enum. A response value outside its enum fails `dto()`
  with a `Hypervel\Data\Exceptions\CannotCastEnum`; the fix is a new case. Where the
  examples contradict a list, the field stays a string (see
  [below](#where-the-references-tables-and-examples-disagree)). The same enums type the query
  parameters that take a listed value, such as `GetSaleList`'s `status`, and `CountryFormat`
  types the one query parameter whose list no field shares (see
  [requests](requests.md#query-parameters)).
- **A verb that takes fewer values says so.** Where a table limits a write ("for POST
  available values are `DRAFT`, `AUTHORISED`"), the write class carries
  `#[In(TaskStatus::Draft, TaskStatus::Authorised)]`, checked with the other rules before
  the body is sent. A field inherited from a parent is redeclared on the write class to
  carry the rule.
- **String fields carry the reference's rules** as validation attributes: `#[Max(n)]` for
  its Length column, `#[Uuid]` for a `Guid`, `#[DateTime]`
  (`Ipsocode\Cin7\Attributes\DateTime`) for a `DateTime`, and `#[Date]` for a `Date`.
  They are checked when the model is sent as a write body, never when a response is read,
  so a response Cin7 sends outside them still becomes a data object. A `Decimal` with a
  Length (the product dimensions' `50`) gets no rule, since `#[Max]` on a number caps its
  value, not its digits.
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
   `Lines.0.SKU`. A required field has to be present, not non-empty: the reference's
   examples send required fields as `""` or `[]` (an order's `Memo`, say), so `""` and `[]`
   pass, and the field's other rules apply only to a value.

The rules run through Hypervel Data's own validation, the `validate()` every data class
has, so there is no validation code per class; `WriteRequest` only asks for `present` where
Hypervel Data would infer `required`.

An array body skips all four: it is sent as given, less the `$omit` paths. That is the way to
send an explicit `null`, to clear a field:

```php
$this->cin7->customer()->put(['ID' => $guid, 'TaxNumber' => null]);           // TaxNumber: null
$this->cin7->customer()->put(CustomerPutData::from([...$customer->toArray(), 'TaxNumber' => null])); // left out
```

Because a data object's `toArray()` carries every field, spreading it into an array body
sends a `null` for each field the caller left unset. To change a model before sending it,
build a data object again:

```php
$this->cin7->customer()->put(CustomerPutData::from([...$customer->toArray(), 'ID' => $guid]));
```

## Classes by path

| Path | Request body (POST/PUT) | `dto()` |
|---|---|---|
| `customer` | POST: `CustomerPostData`; PUT: `CustomerPutData`, which also requires `ID` (Customer, with `Addresses`: `CustomerAddressData`, `Contacts`: `CustomerContactData` and `ProductPrices`: `ProductPriceData`, Customer specific Product Price Model) | GET: `list<CustomerData>`; POST, PUT: `CustomerData`, the saved customer (`CustomerList.0`); responses add `ChildCustomers`: `ChildCustomerData` |
| `product` | POST: `ProductPostData`, which also requires `Type`; PUT: `ProductPutData`, which also requires `ID` (Product, with `Suppliers`: `ProductSupplierData` and its `ProductSupplierOptions`: `ProductSupplierOptionData` and `SupplyIntervals`: `ProductSupplierOptionIntervalData`, `ReorderLevels`: `ReorderLevelData`, `BillOfMaterialsProducts`: `BillOfMaterialProductData`, `BillOfMaterialsServices`: `BillOfMaterialServiceData`, `Movements`: `ProductMovementData`, `Attachments`: `AttachmentLineData` and `CustomPrices`: `ProductPriceData`) | GET: `list<ProductData>`; POST, PUT: `ProductData`, the saved product (`Products.0`) |
| `ref/tax` | POST: `TaxPostData`; PUT: `TaxPutData`, which also requires `ID` (Tax, with `Components`: `TaxComponentData`, Tax Component Model) | GET: `list<TaxData>`; POST, PUT: `TaxData`, the saved rule (`TaxRuleList.0`) |
| `ref/customer/credits` | none | GET: `list<CustomerCreditData>` (Customer Credits) |
| `moneyOperation` | POST: `MoneyTaskPostData`; PUT: `MoneyTaskPutData`, which also requires `TaskID` (Money Task, with `Lines`: `MoneyTaskLineData`, Money Task Line Model) | GET, POST, PUT, DELETE: `MoneyTaskData`, with `Transactions`: `TransactionStockLineData` (Transaction Stock Line Model) and `Attachments`: `AttachmentLineData` |
| `moneyTaskList` | none | GET: `list<MoneyTaskListData>` (Money Task List) |
| `sale` | POST: `SalePostData`; PUT: `SalePutData`, which also requires `ID` (Sale POST/PUT Attributes, with `BillingAddress`: `AddressData`, `ShippingAddress`: `SaleShippingAddressData`, `AdditionalAttributes`: `AdditionalAttributeData`) | GET, POST, PUT, DELETE: `SaleData` (Sale) |
| `saleList` | none | GET: `list<SaleListData>` (Sale List) |
| `saleCreditNoteList` | none | GET: `list<SaleCreditNoteListData>` (Sale Credit Note List) |
| `sale/quote` | POST: `SaleQuotePostData` (Sale Quote, with `Lines`: `SaleQuoteLineData`, `AdditionalCharges`: `SaleAdditionalChargeData` and `Prepayments`: `SalePaymentLineData`) | GET, POST: `SaleQuoteData` |
| `sale/order` | `SaleOrderData` (Sale Order, plus `AutoPickPackShipMode`, which the reference documents only in prose) | GET, POST: `SaleOrderData` |
| `sale/fulfilment` | `SaleFulfilmentsData`, needing only `SaleID` | GET, POST, DELETE: `SaleFulfilmentsData` (`{SaleID, Fulfilments}`, with `Fulfilments`: `SaleFulfilmentData`, Sale Fulfilment Model) |
| `sale/fulfilment/pick` | POST: `SaleFulfilmentPickPostData`; PUT: `SaleFulfilmentPickPutData` (Sale Fulfilment Pick, with `Lines`: `SaleFulfilmentPickPackLineData`, plus `AutoPickMode`, which the reference documents only in prose) | GET, POST, PUT: `SaleFulfilmentPickData` |
| `sale/fulfilment/pack` | POST: `SaleFulfilmentPackPostData`; PUT: `SaleFulfilmentPackData` (Sale Fulfilment Pack, with `Lines`: `SaleFulfilmentPickPackLineData`) | GET, POST, PUT: `SaleFulfilmentPackData` |
| `sale/fulfilment/ship` | POST: `SaleFulfilmentShipPostData`; PUT: `SaleFulfilmentShipPutData`, which adds `AddTrackingNumbers` (Sale Fulfilment Ship, with `ShippingAddress`: `SaleShippingAddressData` and `Lines`: `SaleFulfilmentShipLinePostPutData`) | GET, POST, PUT: `SaleFulfilmentShipData` (with `Lines`: `SaleFulfilmentShipLineData`) |
| `sale/invoice` | POST: `SaleInvoicePostData` (Sale Invoice POST Model); PUT: `SaleInvoicePutData` (its fields, needing only `SaleID` and `TaskID`) | GET, POST, PUT, DELETE: `SaleInvoicesData` (`{SaleID, Invoices}`, with `Invoices`: `SaleInvoicePartialData`, Sale Invoice Partial Model) |
| `sale/creditnote` | POST: `SaleCreditNotePostData` (Sale Credit Note POST Model) | GET, POST, DELETE: `SaleCreditNotesData` (`{SaleID, CreditNotes}`, with `CreditNotes`: `SaleCreditNotePartialData`, Sale Credit Note Invoice Partial Model, whose `Payments` are `SaleCreditNotePaymentData`) |
| `sale/manualJournal` | POST: `SaleManualJournalPostData` (Sale Manual Journal, with `Lines`: `SaleManualJournalLineData`) | GET, POST: `SaleManualJournalData` |
| `sale/attachment` | POST: `SaleAttachmentPostData` (the reference's "Available fields for POST Methods") | GET, POST, DELETE: `SaleAttachmentsData` (`{SaleID, Lines}`, with `Lines`: `AttachmentLineData`) |
| `sale/payment` | POST: `SalePaymentPostData`; PUT: `SalePaymentPutData` (the Sale Payment Line Partial Model's fields for each verb) | GET: `list<SalePaymentLinePartialData>`, a bare array; POST, PUT: `SalePaymentLinePartialData`; DELETE: `{Success}`, left to `json()` |
| any | none | `ErrorData` (Error Model, `{ErrorCode, Exception}`): not a `dto()`, since an Error Model body throws; read it from the exception's response, see [errors](requests.md#errors) |

`SaleData` nests one class per model, each in the folder of the sale path it belongs to:

| Key | Class (reference model) | Folder |
|---|---|---|
| `BillingAddress`, `ShippingAddress`, `AdditionalAttributes` | `AddressData`, `SaleShippingAddressData`, `AdditionalAttributeData` | `src/Data/Sale/` |
| `Quote` | `SaleQuoteData`, with `Prepayments` (`SalePaymentLineData`), `Lines` (`SaleQuoteLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) | `src/Data/Sale/Quote/` |
| `Order` | `SaleOrderData`, with `Lines` (`SaleOrderLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) | `src/Data/Sale/Order/` |
| `Fulfilments` | `SaleFulfilmentData`: `Pick` and `Pack` are `SaleFulfilmentPickPackData` (`Lines`: `SaleFulfilmentPickPackLineData`), `Ship` is `SaleFulfilmentShipData` (`Lines`: `SaleFulfilmentShipLineData`) | `src/Data/Sale/Fulfilment/`, the ship models in `Ship/` |
| `Invoices` | `SaleInvoiceData`, with `Lines` (`SaleInvoiceLineData`), `AdditionalCharges` (`SaleInvoiceAdditionalChargeData`) and `Payments` (`SalePaymentLineData`) | `src/Data/Sale/Invoice/` |
| `CreditNotes` | `SaleCreditNoteData`, with the invoice's lines plus `Refunds` (`SalePaymentLineData`) and `Restock` (`SaleFulfilmentPickPackLineData`) | `src/Data/Sale/CreditNote/` |
| `ManualJournals` | `SaleManualJournalData`, with `Lines` (`SaleManualJournalLineData`) | `src/Data/Sale/ManualJournal/` |
| `Attachments` | `AttachmentLineData`, shared across families | `src/Data/` |
| `InventoryMovements`, `Transactions` | `InventoryMovementLineData`, `SaleTransactionLineData` | `src/Data/Sale/` |

The payment line, additional charge and address classes stay in `src/Data/Sale/` because
several sale paths share them.

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
  lists; it is modelled on `SalePostData`. The example also sends `"SkipQuote": "false"`,
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
  `SupplierCustomerName`, but every example returns `SupplierCustomer`; the money task classes
  model the wire key, `SupplierCustomer`.
- **Money Task `TaskID`.** The table requires it for PUT only, and the POST example has none,
  so `MoneyTaskPostData` has no `TaskID` and `MoneyTaskData` leaves it optional.
- **Money Task Line `TaxRule` and `Account`.** The table names them so, but every example
  sends `TaxRuleName` and `AccountCode`; `MoneyTaskLineData` models the example keys, and
  requires them as the table requires `TaxRule` and `Account`.
- **Tax `ID` and `TaxPercent`.** The table marks no `ID` required, but a PUT changes the rule its
  `ID` names, so `TaxPutData` requires it; `TaxPostData` has none, and `TaxData` leaves it
  optional. `TaxPercent` is read-only, but the request examples send it, so the classes model it
  and the requests leave it out of the body.
- **Tax Component notes.** The notes on `Name` ("Name of product. Read-only.") and `Percent`
  ("Cost. Required if product type is `Service`") are copied from a product table;
  `TaxComponentData` follows the Required column and requires both.
- **Money Task nulls.** `SupplierID`, `CustomerID` and `Note` are `null` in the examples, so
  those properties admit `null`.
- **Customer `AdditionalAttribute#`.** The table lists one row, "# - int(1-10)", with no
  length. On the wire these are ten keys, `AdditionalAttribute1` to `AdditionalAttribute10`,
  which the customer classes, `ProductData` and `AdditionalAttributeData` take from the
  `HasAdditionalAttributes` trait. The Product table and the Additional Attribute Model give
  each 256 characters, and the trait applies that limit to all three.
- **Customer `TaxNumber`.** The table types it `Int`, but every example has `""` or `null`, so
  it is a nullable string. `Discount` and `CreditLimit` are `int`, as the table types them.
- **Customer `ID` and `Status`.** The table marks `ID` required, but the POST example has none,
  since Cin7 assigns it, so `CustomerPostData` has no `ID`. `Status` is required for POST only,
  so `CustomerData` and `CustomerPutData` leave it optional.
- **Customer addresses and contacts.** The examples also send `CustomerID` on each address
  and contact, and `JobTitle` on each contact; the classes model them.
- **One class for two price models.** Product's Custom Price and Customer's Product Price are
  the same Customer specific Product Price Model, so `ProductPriceData` serves both. Its
  footnote requires `ProductID` or `ProductSKU`, and `CustomerID` or `CustomerName`, and a write
  body is checked for both pairs wherever the price is nested, in a customer or a product. The
  response examples nested in either carry no customer; they still become data objects, since
  the rules are checked on write bodies only.
- **Product `PriceTiers`.** The Price Tier Model's one row is named after the account's tier
  (`Tier 1`, or whatever the account renamed it), so it cannot be a set of properties. It is
  an `array<string, float>`, and there is no `PriceTierData`. `PriceTier1` to `PriceTier10`
  are ordinary properties.
- **Product supplier link.** The key is `SupplierProductURL`, as the table names it. The POST
  and PUT examples and their responses send `URL`, which is not the wire key, so
  `ProductSupplierData` models `SupplierProductURL` only and the request fixtures use it.
- **Product `ID` and `Type`.** The table leaves `ID`'s Required column empty, but its notes say
  "Required for PUT action" and "Ignored by POST action", so `ProductPutData` requires it and
  `ProductPostData` has none. `Type` is required and read-only for PUT, so `ProductPostData`
  requires it and `ProductPutData` has none; the PUT example sends no `Type`.
- **Product `PriceTiers`, when required.** The notes call `PriceTiers` required when no
  `PriceTierN` is given. That reads as one of two ways to give prices, not as a requirement that
  every write prices the product, so neither is required.
- **Product supplier `ProductID` and `ProductSKU`.** The table requires one "when not nested
  within the Product"; nested in a product's `Suppliers`, `ProductSupplierData` requires
  neither.
- **Product `Movements.BatchSN`.** The table types it `Decimal`, but the example has `"1"`
  and a batch or serial number is not a quantity, so it is a nullable string.
- **Payment `Type`.** The Sale Payment Line Partial table lists `PREPAYMENT`, `PAYMENT` and
  `REFUND`; every example sends `Payment` or `Refund`, and the notes write `Prepayment`. The
  classes document the examples' spelling and do not restrict the value, so it is a string,
  not an enum.
- **Sale `CombinedInvoiceStatus`.** The Sale and Sale List tables list the invoice statuses
  (`DRAFT`, `AUTHORISED`, `PAID`, …), but the examples send `INVOICED`, `NOT INVOICED` and
  `INVOICED / CREDITED`, the values the purchase tables list for their combined invoice
  status. It stays a string.
- **Product movement `Type`.** The Product Movement Available Types list spells one value
  `Purchase Cost Chang`, so the wire spelling of the list is uncertain; the field stays a
  string.
- **Credit note payments.** The payments of a `sale/creditnote` GET with `IncludePaymentInfo`
  carry `SaleOrderNumber`, `InvoiceNumber`, `CreditNoteNumber`, `Type` and `CreditID` besides
  the Sale Payment Line Model's fields, and no model names them; they are
  `SaleCreditNotePaymentData`, modelled on the example.
- **Partial models and the models embedded in a Sale.** A Sale's `Invoices` carry `Payments`
  and the totals and `Paid`, and its `CreditNotes` carry `Refunds` and the totals; the Partial
  tables and every `sale/invoice` and `sale/creditnote` example have none of them, so the
  partial classes leave them out.
- **Line `ProductID` and `SKU`.** Every line table marks them `Yes*`, required when
  `CombineAdditionalCharges` is set; the line classes require them always, with `Name`,
  `Quantity`, `Price`, `Tax` and `TaxRule`, which every line table requires.
- **Sale `Location`, `CurrencyRate` and customer.** The Sale POST/PUT table requires
  `Location`, `CurrencyRate` when the customer's currency differs from the base currency, and
  `Customer` when there is no `CustomerID`; the Sale table of the response requires none of
  them. `SaleData`, `SalePostData` and `SalePutData` require `Location` and `CurrencyRate` always. A write
  body needs `Customer` or `CustomerID`: each carries `#[RequiredWithout]` naming the other,
  so a sale body with neither fails validation before it is sent.
- **Required per verb.** The tables have one Required column for every verb. The
  `sale/invoice` PUT notes allow leaving attributes out, so `SaleInvoicePutData` requires only
  `SaleID` and `TaskID`; a payment PUT may not carry `Amount` or `Account` when it is a
  credit, so `SalePaymentPutData` requires only `ID`.
- **Payment `CreditID` on POST.** The table makes `CreditID` PUT-only, but the POST example
  sends `"CreditID": null`; `SalePaymentPostData` has no `CreditID`.
- **Auto-generated numbers.** The invoice and credit note POST tables have no `InvoiceNumber`
  or `CreditNoteNumber` (Cin7 generates them), so the POST classes leave them out.
- **Fulfilment pick and pack.** The Sale Fulfilment Pick and Pack tables, `sale/fulfilment/pick`
  and `/pack`, add the fulfilment's `TaskID` to the Pick Pack Model a fulfilment embeds;
  `SaleFulfilmentPickData` and `SaleFulfilmentPackData` model them and require it, and
  `SaleFulfilmentPickPackData` stays the embedded model. Only POST limits `Status` to `DRAFT`
  and `AUTHORISED`, so the pack's PUT body is `SaleFulfilmentPackData` itself.
- **Autopick.** The pick's POST and PUT notes say a body of `TaskID` and `AutoPickMode`
  (`AUTOPICK`) picks the task and authorises it. The table requires `Status`, so the pick bodies
  require it unless `AutoPickMode` is given (`#[RequiredWithout('AutoPickMode')]`), and model
  `AutoPickMode` as a string.
- **Fulfilment ship.** The Sale Fulfilment Ship Model a fulfilment embeds and the Sale
  Fulfilment Ship table of `sale/fulfilment/ship` share a name, so `SaleFulfilmentShipData` is
  both, with the table's `TaskID` optional. A body's line names its box `Box`, as the line table
  says, where a response's says `Boxes`, so body lines are `SaleFulfilmentShipLinePostPutData`;
  it models the read-only `TrackingURL` the examples send, which the requests leave out.
  `AddTrackingNumbers`, in the PUT notes only, is on `SaleFulfilmentShipPutData`.
- **`IncludeProductInfo`.** It adds "all used products in additional array" to a fulfilment,
  pick or pack response; no example shows the array, so it is not modelled, and `json()` still
  has it.
- **Sale Credit Note List.** Its table is the Sale List's, field for field, so `SaleListData` and
  `SaleCreditNoteListData` share `AbstractSaleListData`. The credit note list's example sends
  `QuoteStatus` as `""`, outside the quote statuses, and `CombinedTrackingNumbers` as `null`, so
  its `QuoteStatus` is a string and its `CombinedTrackingNumbers` optional; `RestockStatus`
  appears only in its example. Both tables type `Customer` as `Date`; it is the customer's name.
- **Sale quote and manual journal.** As with the order, the Sale Quote and Sale Manual Journal
  tables of `sale/quote` and `sale/manualJournal` add `SaleID` (and the quote
  `CombineAdditionalCharges`) to the models a sale embeds, so `SaleQuoteData` and
  `SaleManualJournalData` serve both, with those optional. Their POST bodies require them, and
  limit `Status` to `DRAFT` and `AUTHORISED`; the quote's POST requires no totals ("Not required
  for POST"), and its example sends none.
- **Sale attachment delete.** The reference marks the `ID` of `DELETE sale/attachment` optional;
  a delete names what it deletes, so `DeleteSaleAttachment` requires it. The POST example's base64
  `Content` is a 62 KB image; the fixture keeps its first 32 characters.
- **Examples that are not valid JSON** are fixed when they become a fixture in
  `Cin7Payloads`, not copied verbatim.

## Customers

`customer` follows the Customer table, with a class per verb because `ID` and `Status` are
required on different verbs. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `CustomerData` (response) | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `ID` |
| `CustomerPostData` | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `Status` |
| `CustomerPutData` | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `ID` |
| `CustomerAddressData` | `src/Data/Customer/` | `Line1`, `Country`, `Type` |
| `CustomerContactData` | `src/Data/Customer/` | `Name` |
| `ProductPriceData` | `src/Data/` | `Price`; and on a write body `ProductID` or `ProductSKU`, and `CustomerID` or `CustomerName` (`#[RequiredWithout]`) |

`LastModifiedOn` (read-only) and `ChildCustomers` (responses only) are on `CustomerData` alone.
A response missing a required field fails `dto()` with a `CannotCreateData`.

## Products

`product` follows the Product table, with a class per verb because `ID` and `Type` are each
taken by one verb only. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `ProductData` (response) | `src/Data/Product/` | `SKU`, `Name`, `Category`, `CostingMethod`, `UOM`, `Status` |
| `ProductPostData` | `src/Data/Product/` | `SKU`, `Name`, `Category`, `CostingMethod`, `UOM`, `Status`, `Type` |
| `ProductPutData` | `src/Data/Product/` | `SKU`, `Name`, `Category`, `CostingMethod`, `UOM`, `Status`, `ID` |
| `ReorderLevelData` | `src/Data/Product/` | `PickZones` |
| `BillOfMaterialProductData` | `src/Data/Product/` | `Quantity` |
| `BillOfMaterialServiceData` | `src/Data/Product/` | `Quantity` |

The tables' conditions are rules a write body is checked against before it is sent:

- `QuantityToProduce` and `AssemblyCostEstimationMethod`, when `BillOfMaterial` is `true`
  (`#[RequiredIf]`);
- one of `SupplierID` and `SupplierName` on a supplier, `LocationID` and `LocationName` on a
  supplier option and on a reorder level, `ComponentProductID` and `ProductCode` on a component,
  and `ComponentProductID` and `Name` on a service (`#[RequiredWithout]`, naming the other);
- `IntervalDays` and `IntervalStartDate` for an `Interval` supply interval, and `IsMonday` to
  `IsSunday` for a `Fixed` one, which the interval table's notes require (`#[RequiredIf]`).

`AverageCost`, `LastModifiedOn` and `BOMType` (read-only) are on `ProductData` alone. A response
missing a required field fails `dto()` with a `CannotCreateData`.

## Tax rules and money tasks

| Class | Folder | Required |
|---|---|---|
| `TaxData` (response) | `src/Data/Ref/Tax/` | `Name`, `Account`, `IsActive`, `TaxInclusive` |
| `TaxPostData` | `src/Data/Ref/Tax/` | `Name`, `Account`, `IsActive`, `TaxInclusive` |
| `TaxPutData` | `src/Data/Ref/Tax/` | `Name`, `Account`, `IsActive`, `TaxInclusive`, `ID` |
| `TaxComponentData` | `src/Data/Ref/Tax/` | `Name`, `Percent`, `AccountCode`, `ComponentOrder` |
| `MoneyTaskData` (response) | `src/Data/MoneyTask/` | `TaskType`, `Status`, `BankAccount`, `Date` |
| `MoneyTaskPostData` | `src/Data/MoneyTask/` | `TaskType`, `Status`, `BankAccount`, `Date` |
| `MoneyTaskPutData` | `src/Data/MoneyTask/` | `TaskType`, `Status`, `BankAccount`, `Date`, `TaskID` |
| `MoneyTaskLineData` | `src/Data/MoneyTask/` | `Name`, `Quantity`, `TaxRuleName`, `AccountCode`, `Total` |

A response missing a required field fails `dto()` with a `CannotCreateData`.

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

They reuse the invoice line and additional charge classes from `src/Data/Sale/Invoice/` and the
restock line from `src/Data/Sale/Fulfilment/`. A response
missing a required field fails `dto()` with a `CannotCreateData`.

- The credit note Partial table does not list `CreditNoteInvoiceNumber`, which every example
  carries, nor `CreditNoteBalance` and `Payments`, which `IncludePaymentInfo` adds;
  `SaleCreditNotePartialData` models them from the examples.
- On `sale/invoice` PUT, an empty collection deletes the existing records (see
  [the empty-collection rule](#the-empty-collection-rule)).
- The reference's `sale/invoice` and `sale/creditnote` examples carry trailing commas, and the
  credit note POST example an unquoted `SaleID:` key; the fixtures are the corrected JSON.
