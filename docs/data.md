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
  across families in `src/Data/Other/`, after the reference's Other Models
  (`ProductPriceData`, `CustomerAddressData`, `CustomerContactData`, `AttachmentLineData`,
  `ErrorData`, the `AddressData`, `AdditionalAttributeData`, `SalePaymentLineData` and
  `InventoryMovementLineData` a sale and a purchase both carry, and the `PurchaseShippingAddressData`,
  `PurchaseAdditionalChargeData` and `PurchaseUnStockLineData` the simple and the advanced
  purchase share). An abstract parent whose children span families stays in `src/Data/` itself
  (`AbstractLineData`, `AbstractChargeData`, `AbstractAddressData`, `AbstractSalePaymentLineData`,
  `AbstractManualJournalLineData`, `AbstractPurchaseData`, `AbstractPurchaseStockLineData`,
  `AbstractPurchaseInvoiceData`, `AbstractPurchaseCreditNoteData`, `AbstractPurchasePaymentData`,
  `AbstractPurchaseManualJournalData`). The Money Task's classes are in `src/Data/MoneyTask/`, like
  its requests (see [resources](resources.md#conventions)). `src/Data/` holds nothing else: the
  traits the models share are in `src/Concerns/` and the validation attribute in `src/Attributes/`.
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
  | `AbstractSupplierData` | `SupplierData`, `SupplierPostData`, `SupplierPutData` | `Name`, `Currency`, `PaymentTerm`, `AccountPayable`, `TaxRule` |
  | `AbstractMeAddressData` | `MeAddressData`, `MeAddressPostData`, `MeAddressPutData` | `Line1`, `CitySuburb`, `StateProvince`, `ZipPostCode`, `Country`, `Type` |
  | `AbstractMeContactData` | `MeContactData`, `MeContactPostData`, `MeContactPutData` | `Name` |
  | `AbstractMoneyTaskData` | `MoneyTaskData`, `MoneyTaskPostData`, `MoneyTaskPutData` | `TaskType`, `Status`, `BankAccount`, `Date` |
  | `AbstractTaxData` | `TaxData`, `TaxPostData`, `TaxPutData` | `Name`, `Account`, `IsActive`, `TaxInclusive` |
  | `AbstractProductData` | `ProductData`, `ProductPostData`, `ProductPutData` | `SKU`, `Name`, `Category`, `CostingMethod`, `UOM`, `Status`; and `QuantityToProduce` and `AssemblyCostEstimationMethod` on a write body with a bill of materials (see [products](#products)) |
  | `AbstractSaleListData` | `SaleListData`, `SaleCreditNoteListData` | the 19 fields both list tables require but `QuoteStatus` and `CombinedTrackingNumbers` (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractPurchaseListData` | `PurchaseListData`, `PurchaseCreditNoteListData`, which add no field of their own (see [below](#where-the-references-tables-and-examples-disagree)) | `CombinedReceivingStatus`, `CombinedInvoiceStatus`, `CombinedPaymentStatus`, `Type` |
  | `AbstractSaleQuoteData` | `SaleQuoteData`, `SaleQuotePostData` | `Memo`, `Status`, `Lines` |
  | `AbstractSaleManualJournalData` | `SaleManualJournalData`, `SaleManualJournalPostData` | `Status` |
  | `AbstractSaleData` | `SaleData`, `SalePostData`, `SalePutData` | `Location`, `CurrencyRate`; and `Customer` or `CustomerID` on a write body (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractSaleInvoiceData` | `SaleInvoiceData`, `SaleInvoicePartialData`, `SaleInvoicePostData`, `SaleInvoicePutData` | `TaskID` |
  | `AbstractSaleFulfilmentPickPackTaskData` | `SaleFulfilmentPickData`, `SaleFulfilmentPickPostData`, `SaleFulfilmentPickPutData`, `SaleFulfilmentPackData`, `SaleFulfilmentPackPostData` | `TaskID` |
  | `AbstractSaleFulfilmentShipTaskData` | `SaleFulfilmentShipPostData`, `SaleFulfilmentShipPutData` | `TaskID`, `Status` (`DRAFT`, `PARTIALLY AUTHORISED` or `AUTHORISED`) |
  | `AbstractSaleCreditNoteData` | `SaleCreditNoteData`, `SaleCreditNotePartialData`, `SaleCreditNotePostData` | `TaskID`, `Status`, `CreditNoteDate` |
  | `AbstractPurchaseData` | `PurchaseData`, `PurchasePostData`, `PurchasePutData`, `AdvancedPurchaseData`, `AdvancedPurchasePostData`, `AdvancedPurchasePutData` | `Location`; and `Approach` on every child but `AdvancedPurchasePutData`, and `Supplier` or `SupplierID` on a write body (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractPurchaseOrderData` | `PurchaseOrderData`, `PurchaseOrderPostData` | `Status`, `Lines`; and `Memo` on the POST body (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractPurchaseStockData` | `PurchaseStockData`, `PurchaseStockPostData` | `Status`, `Lines` |
  | `AbstractPurchaseCreditNoteData` | `PurchaseCreditNoteData`, `PurchaseCreditNotePostData`, `AdvancedPurchasePartialCreditNoteData`, `AdvancedPurchasePartialCreditNotePostData`, `AdvancedPurchaseCreditNoteData` | `CreditNoteNumber`, `Status`, `Lines`, `Unstock`; and `CreditNoteDate` on every child but `AdvancedPurchaseCreditNoteData` (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractPurchaseInvoiceData` | `PurchaseInvoiceData`, `PurchaseInvoicePostData`, `AdvancedPurchasePartialInvoiceData`, `AdvancedPurchasePartialInvoicePostData`, `AdvancedPurchaseInvoiceData` | `Status`, `Lines`; and `InvoiceDate` on every child but `AdvancedPurchaseInvoiceData`, and `InvoiceDueDate` on every child but it and `PurchaseInvoiceData` (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractPurchasePaymentData` | `PurchasePaymentData`, `PurchasePaymentPostData`, `PurchasePaymentPutData`, `AdvancedPurchasePaymentData`, `AdvancedPurchasePaymentPostData`, `AdvancedPurchasePaymentPutData` | `TaskID`, `DatePaid`, `CurrencyRate` |
  | `AbstractPurchaseManualJournalData` | `PurchaseManualJournalData`, `PurchaseManualJournalPostData`, `AdvancedPurchasePartialManualJournalData`, `AdvancedPurchasePartialManualJournalPostData`, `AdvancedPurchaseManualJournalData` | `Status` |
  | `AbstractAdvancedPurchaseStockData` | `AdvancedPurchaseStockData`, `AdvancedPurchaseStockPostData`, `AdvancedPurchaseStockPutData` | `Status`, `Lines` |
  | `AbstractAdvancedPurchasePutAwayData` | `AdvancedPurchasePutAwayData`, `AdvancedPurchasePutAwayPostData` | `Status`, `Lines` |
  | `AbstractLineData` | `SaleQuoteLineData`, `SaleOrderLineData`, `SaleInvoiceLineData`, `PurchaseOrderLineData`, `PurchaseInvoiceLineData` | `ProductID`, `SKU`, `Name`, `Quantity`, `Price`, `Tax`, `TaxRule` |
  | `AbstractChargeData` | `SaleAdditionalChargeData`, `SaleInvoiceAdditionalChargeData`, `PurchaseAdditionalChargeData`, `PurchaseInvoiceAdditionalChargeData` | `Description`, `Quantity`, `Price`, `Tax`, `TaxRule` |
  | `AbstractSalePaymentLineData` | `SalePaymentLineData`, `SaleCreditNotePaymentData`, `PurchasePaymentLineData` | none |
  | `AbstractManualJournalLineData` | `SaleManualJournalLineData`, `PurchaseManualJournalLineData` | `Amount`, `Date`, `Debit`, `Credit` |
  | `AbstractPurchaseStockLineData` | `PurchaseStockLineData`, `AdvancedPurchaseStockLineData`, `AdvancedPurchasePutAwayLineData` | `Date`, `Quantity`; and `Location` or `LocationID` on a write body (`#[RequiredWithout]`) of `PurchaseStockLineData` and `AdvancedPurchasePutAwayLineData`, which declare the pair, as `AdvancedPurchaseStockLineData` does without the rule (see [below](#where-the-references-tables-and-examples-disagree)) |
  | `AbstractAddressData` | `AddressData`, `SaleShippingAddressData`, `PurchaseShippingAddressData` | none; each child requires `Line1` and `Country`: `SaleShippingAddressData` by type, `AddressData` and `PurchaseShippingAddressData` on a write body (`#[Required]`) (see [below](#where-the-references-tables-and-examples-disagree)) |

  The line and charge requirements hold in the purchase tables as well, so a purchase model
  can extend those parents. `Account`, which the purchase invoice tables require and the sale
  invoice tables do not, belongs on the children, and so does `Total`: the Purchase Order Line
  and Purchase Additional Charge Models require it and the sale charge tables do not, so each
  line and charge class declares its own.

  Two field sets several unrelated models carry are traits in `src/Concerns/`:
  `HasProductFields` (the product fields of every line with a `ProductID`) and
  `HasAdditionalAttributes` (`AdditionalAttribute1` to `10`).
- **Property names are the wire keys, verbatim** (`ID`, `TaxRuleList`), with no name
  mapper, so `toArray()` is the JSON Cin7 expects.
- **A required field has no default.** It is not nullable, and the model cannot be built
  without it: `from()` throws a `Hypervel\Data\Exceptions\CannotCreateData`. Required fields
  come first in the constructor. Every class requires the fields its table does (see
  [customers](#customers), [suppliers](#suppliers), [me](#me), [products](#products),
  [tax rules and money tasks](#tax-rules-and-money-tasks),
  [sale invoices, credit notes and payments](#sale-invoices-credit-notes-and-payments) and
  [purchases](#purchases)), as do
  the parents above; the Money Task List, Customer Credits, Supplier Deposits, ME and Rounding
  Table tables require none. Where the reference requires different fields per verb, the body
  is a class per verb.
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
  what Cin7 expects. Where the wire values are abbreviations, the cases take the names the
  reference gives them: `WeightUnit::Gram` is `'g'` and `AdjustmentRule::NoAdjustment` is `'N'`.
  Fields with the same list share one enum, and a field whose list is a
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
| `supplier` | POST: `SupplierPostData`; PUT: `SupplierPutData`, which also requires `ID` (Supplier, with `Addresses`: `CustomerAddressData` and `Contacts`: `CustomerContactData`, the Supplier Address and Contact Models the customer shares) | GET: `list<SupplierData>`; POST, PUT: `SupplierData`, the saved supplier (`SupplierList.0`) |
| `product` | POST: `ProductPostData`, which also requires `Type`; PUT: `ProductPutData`, which also requires `ID` (Product, with `Suppliers`: `ProductSupplierData` and its `ProductSupplierOptions`: `ProductSupplierOptionData` and `SupplyIntervals`: `ProductSupplierOptionIntervalData`, `ReorderLevels`: `ReorderLevelData`, `BillOfMaterialsProducts`: `BillOfMaterialProductData`, `BillOfMaterialsServices`: `BillOfMaterialServiceData`, `Movements`: `ProductMovementData`, `Attachments`: `AttachmentLineData` and `CustomPrices`: `ProductPriceData`) | GET: `list<ProductData>`; POST, PUT: `ProductData`, the saved product (`Products.0`) |
| `ref/tax` | POST: `TaxPostData`; PUT: `TaxPutData`, which also requires `ID` (Tax, with `Components`: `TaxComponentData`, Tax Component Model) | GET: `list<TaxData>`; POST, PUT: `TaxData`, the saved rule (`TaxRuleList.0`) |
| `ref/customer/credits` | none | GET: `list<CustomerCreditData>` (Customer Credits) |
| `ref/supplier/deposits` | none | GET: `list<SupplierDepositData>` (Supplier Deposits) |
| `ref/account` | POST: `AccountPostData`, which also takes `SystemAccount` and `SystemAccountCode`; PUT: `AccountPutData` (Chart of Accounts) | GET: `list<AccountData>`; POST, PUT: `AccountData`, the saved account (`AccountsList.0`); DELETE: `{Success}`, left to `json()` |
| `ref/account/bank` | none | GET: `list<BankAccountData>` (Bank Accounts) |
| `ref/brand` | POST: `BrandPostData`; PUT: `BrandPutData`, which also requires `ID` (Brand) | GET: `list<BrandData>`; POST, PUT: `BrandData`, the saved record, a bare object and not a list; DELETE: `{Success}`, left to `json()` |
| `ref/category` | POST: `ProductCategoryPostData`; PUT: `ProductCategoryPutData`, which also requires `ID` (Product Category) | GET: `list<ProductCategoryData>`; POST, PUT: `ProductCategoryData`, the saved record, a bare object and not a list; DELETE: `{Success}`, left to `json()` |
| `ref/unit` | POST: `UnitOfMeasurePostData`; PUT: `UnitOfMeasurePutData`, which also requires `ID` (Unit of Measure) | GET: `list<UnitOfMeasureData>`; POST, PUT: `UnitOfMeasureData`, the saved record, a bare object and not a list; DELETE: `{Success}`, left to `json()` |
| `ref/fixedassettype` | POST: `FixedAssetTypePostData`; PUT: `FixedAssetTypePutData`, which also requires `FixedAssetTypeID` (Fixed Asset Types) | GET: `list<FixedAssetTypeData>`; POST, PUT: `FixedAssetTypeData`, the saved type (`FixedAssetTypeList.0`) |
| `ref/paymentterm` | POST: `PaymentTermPostData`; PUT: `PaymentTermPutData`, which also requires `ID` (Payment Term) | GET: `list<PaymentTermData>`; POST, PUT: `PaymentTermData`, the saved term (`PaymentTermList.0`); DELETE: `{Success}`, left to `json()` |
| `me` | none | GET: `MeData` (ME, with `RoundingTable`: `RoundingTableData`, Rounding Table Model) |
| `me/addresses` | POST: `MeAddressPostData`; PUT: `MeAddressPutData`, which also requires `AddressID` (Me Address) | GET: `list<MeAddressData>`; POST, PUT: `MeAddressData`, the saved address (`MeAddressesList.0`); DELETE: `{Success}`, left to `json()` |
| `me/contacts` | POST: `MeContactPostData`; PUT: `MeContactPutData`, which also requires `ContactID` (Me Contact) | GET: `list<MeContactData>`; POST, PUT: `MeContactData`, the saved contact (`MeContactsList.0`); DELETE: `{Success}`, left to `json()` |
| `bankTransfer` | POST: `BankTransferPostData`; PUT: `BankTransferPutData`, which also requires `TaskID` (Bank Transfer, whose table heading says "Money Task List") | GET, POST, PUT, DELETE: `BankTransferData`, with `Transactions`: `TransactionStockLineData` and `Attachments`: `AttachmentLineData` |
| `journal` | POST: `JournalPostData`; PUT: `JournalPutData`, which also requires `TaskID` (Journal, with `Lines`: `JournalLineData`, Journal Line Model) | GET: `list<JournalData>`, with `Attachments`: `AttachmentLineData`; POST, PUT, DELETE: `JournalData`, the journal (`Journals.0`) |
| `transactions` | none | GET: `list<TransactionData>` (Transactions) |
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
| `purchase` | POST: `PurchasePostData`; PUT: `PurchasePutData`, which also requires `ID` (Purchase POST/PUT Attributes, with `BillingAddress`: `AddressData`, `ShippingAddress`: `PurchaseShippingAddressData`, Purchase Shipping Address Model, and `AdditionalAttributes`: `AdditionalAttributeData`) | GET, POST, PUT, DELETE: `PurchaseData` (Available Fields for Purchase, with `Order`: `PurchaseOrderData`, `StockReceived`: `PurchaseStockData`, `Invoice`: `PurchaseInvoiceData`, `CreditNote`: `SimplePurchaseCreditNoteData`, whose `Unstock` is `PurchaseUnStockData`, `ManualJournals`: `PurchaseManualJournalData`, `Attachments`: `AttachmentLineData` and `InventoryMovements`: `InventoryMovementLineData`) |
| `purchaseList` | none | GET: `list<PurchaseListData>` (Purchase List) |
| `purchaseCreditNoteList` | none | GET: `list<PurchaseCreditNoteListData>` (Purchase Credit Note List), read from `PurchaseList` |
| `purchase/order` | POST: `PurchaseOrderPostData` (Available Fields for Purchase Order, with `Lines`: `PurchaseOrderLineData` and `AdditionalCharges`: `PurchaseAdditionalChargeData`) | GET, POST: `PurchaseOrderData` |
| `purchase/stock` | POST: `PurchaseStockPostData` (Available Fields for Purchase Stock Received, with `Lines`: `PurchaseStockLineData`) | GET, POST: `PurchaseStockData` |
| `purchase/invoice` | POST: `PurchaseInvoicePostData` (Available Fields for Purchase Invoice, with `Lines`: `PurchaseInvoiceLineData` and `AdditionalCharges`: `PurchaseInvoiceAdditionalChargeData`) | GET, POST: `PurchaseInvoiceData`, the invoice (the table and the Purchase Invoice Model a purchase embeds, whose `Payments` are `SalePaymentLineData`) |
| `purchase/creditnote` | POST: `PurchaseCreditNotePostData` (Available Fields for Purchase Credit Note, with `Lines`: `PurchaseInvoiceLineData`, `AdditionalCharges`: `PurchaseInvoiceAdditionalChargeData` and `Unstock`: `PurchaseUnStockLineData`) | GET, POST: `PurchaseCreditNoteData`, the credit note (the table and the Purchase Credit Note Model, whose `Refunds` are `SalePaymentLineData`; the credit note a purchase embeds is read by `SimplePurchaseCreditNoteData`) |
| `purchase/payment` | POST: `PurchasePaymentPostData`; PUT: `PurchasePaymentPutData`, which also requires `ID` (Available Fields for Purchase Payments, the fields each verb takes) | GET: `list<PurchasePaymentData>`, a bare array; POST, PUT: `PurchasePaymentData`, the saved payment; DELETE: `{Success}`, left to `json()` |
| `purchase/manualJournal` | POST: `PurchaseManualJournalPostData` (Available field for Purchase Manual Journal, with `Lines`: `PurchaseManualJournalLineData`) | GET, POST: `PurchaseManualJournalData` |
| `purchase/attachment` | POST: `PurchaseAttachmentPostData` (the reference's "Available fields for POST Methods") | GET, POST, DELETE: `PurchaseAttachmentsData` (`{TaskID, Lines}`, with `Lines`: `AttachmentLineData`) |
| `advanced-purchase` | POST: `AdvancedPurchasePostData`, which also takes the POST-only `PurchaseType`; PUT: `AdvancedPurchasePutData`, which also requires `ID` and leaves `Approach` optional (Purchase POST/PUT Attributes, with `BillingAddress`: `AddressData`, `ShippingAddress`: `PurchaseShippingAddressData` and `AdditionalAttributes`: `AdditionalAttributeData`) | GET, POST, PUT, DELETE: `AdvancedPurchaseData` (Available Fields for Purchase, with `Order`: `PurchaseOrderData`, `StockReceived`: `AdvancedPurchaseStockData`, `PutAway`: `AdvancedPurchasePutAwayData`, `Invoice`: `AdvancedPurchaseInvoiceData` (Advanced Purchase Invoice Model, whose `Payments` are `PurchasePaymentLineData`), `CreditNote`: `AdvancedPurchaseCreditNoteData` (Advanced Purchase Credit Note Model, whose `Refunds` are `PurchasePaymentLineData`), `ManualJournals`: `AdvancedPurchaseManualJournalData` (Advanced Purchase Manual Journal Model), each a list, `Attachments`: `AttachmentLineData` and `InventoryMovements`: `InventoryMovementLineData`) |
| `advanced-purchase/stock` | POST: `AdvancedPurchaseStockPostData`; PUT: `AdvancedPurchaseStockPutData`, which also requires `TaskID` (Available Fields for Purchase Stock Received, with `Lines`: `AdvancedPurchaseStockLineData`, Advanced Purchase Stock Line Model) | GET, POST, PUT, DELETE: `AdvancedPurchaseStocksData` (`{PurchaseID, StockReceiving}`, with `StockReceiving`: `AdvancedPurchaseStockData`, Advanced Purchase Stock Model) |
| `advanced-purchase/put-away` | POST: `AdvancedPurchasePutAwayPostData` (Available Fields for Purchase Put Away, with `Lines`: `AdvancedPurchasePutAwayLineData`, Advanced Purchase Put Away Line Model) | GET, POST: `AdvancedPurchasePutAwaysData` (`{PurchaseID, PutAway}`, with `PutAway`: `AdvancedPurchasePutAwayData`, Advanced Purchase Put Away Model) |
| `advanced-purchase/invoice` | POST: `AdvancedPurchasePartialInvoicePostData` (Advanced purchase invoice partial model, plus the purchase's `PurchaseID`, with `Lines`: `PurchaseInvoiceLineData` and `AdditionalCharges`: `PurchaseInvoiceAdditionalChargeData`) | GET, POST, DELETE: `AdvancedPurchaseInvoicesData` (Available Fields for Purchase Invoice, `{PurchaseID, Invoices}`, with `Invoices`: `AdvancedPurchasePartialInvoiceData`, Advanced purchase invoice partial model) |
| `advanced-purchase/creditnote` | POST: `AdvancedPurchasePartialCreditNotePostData` (one Advanced purchase credit note partial model plus the purchase's `PurchaseID`, with `Lines`: `PurchaseInvoiceLineData`, `AdditionalCharges`: `PurchaseInvoiceAdditionalChargeData` and `Unstock`: `PurchaseUnStockLineData`) | GET, POST, DELETE: `AdvancedPurchaseCreditNotesData` (`{PurchaseID, CreditNotes}`, Available Fields for Purchase Credit Note, with `CreditNotes`: `AdvancedPurchasePartialCreditNoteData`, Advanced purchase credit note partial model) |
| `advanced-purchase/payment` | POST: `AdvancedPurchasePaymentPostData`; PUT: `AdvancedPurchasePaymentPutData`, which also requires `ID` (Available Fields for Purchase Payments, the `purchase/payment` table, the fields each verb takes) | GET: `list<AdvancedPurchasePaymentData>`, a bare array; POST, PUT: `AdvancedPurchasePaymentData`, the saved payment, with the `PurchaseID` the examples add; DELETE: sent to `purchase/payment` (`DeletePurchasePayment`), its `{Success}` left to `json()` |
| `advanced-purchase/manualJournal` | POST: `AdvancedPurchasePartialManualJournalPostData` (Advanced purchase manual journal partial model, plus `PurchaseID`, with `Lines`: `PurchaseManualJournalLineData`) | GET, POST: `AdvancedPurchaseManualJournalsData` (`{PurchaseID, ManualJournals}`, Available field for Purchase Manual Journal, with `ManualJournals`: `AdvancedPurchasePartialManualJournalData`) |
| any | none | `ErrorData` (Error Model, `{ErrorCode, Exception}`): not a `dto()`, since an Error Model body throws; read it from the exception's response, see [errors](requests.md#errors) |

`SaleData` nests one class per model, each in the folder of the sale path it belongs to:

| Key | Class (reference model) | Folder |
|---|---|---|
| `BillingAddress`, `AdditionalAttributes` | `AddressData`, `AdditionalAttributeData`, shared with the purchase | `src/Data/Other/` |
| `ShippingAddress` | `SaleShippingAddressData` | `src/Data/Sale/` |
| `Quote` | `SaleQuoteData`, with `Prepayments` (`SalePaymentLineData`), `Lines` (`SaleQuoteLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) | `src/Data/Sale/Quote/` |
| `Order` | `SaleOrderData`, with `Lines` (`SaleOrderLineData`) and `AdditionalCharges` (`SaleAdditionalChargeData`) | `src/Data/Sale/Order/` |
| `Fulfilments` | `SaleFulfilmentData`: `Pick` and `Pack` are `SaleFulfilmentPickPackData` (`Lines`: `SaleFulfilmentPickPackLineData`), `Ship` is `SaleFulfilmentShipData` (`Lines`: `SaleFulfilmentShipLineData`) | `src/Data/Sale/Fulfilment/`, the ship models in `Ship/` |
| `Invoices` | `SaleInvoiceData`, with `Lines` (`SaleInvoiceLineData`), `AdditionalCharges` (`SaleInvoiceAdditionalChargeData`) and `Payments` (`SalePaymentLineData`) | `src/Data/Sale/Invoice/` |
| `CreditNotes` | `SaleCreditNoteData`, with the invoice's lines plus `Refunds` (`SalePaymentLineData`) and `Restock` (`SaleFulfilmentPickPackLineData`) | `src/Data/Sale/CreditNote/` |
| `ManualJournals` | `SaleManualJournalData`, with `Lines` (`SaleManualJournalLineData`) | `src/Data/Sale/ManualJournal/` |
| `Attachments` | `AttachmentLineData`, shared across families | `src/Data/Other/` |
| `InventoryMovements` | `InventoryMovementLineData`, shared with the purchase | `src/Data/Other/` |
| `Transactions` | `SaleTransactionLineData` | `src/Data/Sale/` |

The additional charge and shipping address classes stay in `src/Data/Sale/` because several
sale paths share them. The billing address, additional attributes, payment line
(`SalePaymentLineData`, a quote's `Prepayments`) and inventory movement line are in
`src/Data/Other/` because the purchase family carries them too. The billing address takes a `null`
`Line1` and `Country` in a response but requires both on a write body, for both families (see
[below](#where-the-references-tables-and-examples-disagree)).

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
- **Purchase Order.** As with the sale order, the reference documents the model twice: the
  Purchase Order Model a purchase embeds as its `Order` (with `Prepayments`, Sale Payment Line
  Models), and `purchase/order`'s Available Fields for Purchase Order (which adds `TaskID` and
  `CombineAdditionalCharges`). `PurchaseOrderData` carries the union, with those three optional,
  as only one table has each. The POST body is `PurchaseOrderPostData`, as the quote's is: it
  requires `TaskID` and `CombineAdditionalCharges`, limits `Status` to `DRAFT` and `AUTHORISED`,
  leaves the totals optional ("Not required for POST"; the POST example sends none), and takes no
  `Prepayments`, which the `purchase/order` table does not list. Both tables require `Memo`, but the
  `purchase` POST, PUT and DELETE examples embed an order that is `NOT AVAILABLE` or `VOIDED` with
  a `null` `Memo`: `PurchaseOrderData` leaves it optional and `PurchaseOrderPostData` requires it,
  so each declares its own and `AbstractPurchaseOrderData` takes only `Status` and `Lines`, as the
  sale lists each declare their `CombinedTrackingNumbers`.
- **Purchase Stock Received.** The reference documents the model twice: the Purchase Stock Model a
  purchase embeds as its `StockReceived`, and `purchase/stock`'s Available Fields for Purchase Stock
  Received, which adds the purchase's `TaskID`. `PurchaseStockData` carries the union, with `TaskID`
  optional, as only one table has it. The POST body is `PurchaseStockPostData`: it requires
  `TaskID`, and limits `Status` to `DRAFT` and `AUTHORISED` ("For POST only"). `Lines` is required,
  and `[]` passes, which is how a POST authorises the stock received.
- **Purchase stock line.** Its `ProductID` and `SKU` are a bare `Yes*`, with no condition, unlike
  the priced lines' (see below), so `PurchaseStockLineData` leaves both optional, as the supplier's
  `Status`. `Location` and `LocationID` are each required if the other is empty
  (`#[RequiredWithout]`). `Name` and `Received` are read-only, and the POST example sends both, so
  the class models them and `PostPurchaseStock` leaves them out of the body. The POST response's
  `CardID` (the stock batch) is not the one its request sent; the table does not mark it read-only,
  so it is modelled and sent.
- **Purchase charge `Total`.** The Purchase Additional Charge Model requires the charge's `Total`,
  which the sale and purchase invoice charge tables leave optional; `AbstractChargeData` no longer
  declares it, `PurchaseAdditionalChargeData` requires it, and the sale charge classes keep it
  optional.
- **Sale POST/PUT.** The POST example sends `AutoPickPackShipMode`, which no Sale table
  lists; it is modelled on `SalePostData`. The example also sends `"SkipQuote": "false"`,
  `"TaxInclusive": "false"` and `"CurrencyRate": "1"` as strings; the properties are `bool`
  and `float`, following the tables.
- **Purchase POST/PUT.** The Purchase POST/PUT Attributes require `Approach` and `Location`, `ID`
  for PUT only, and `Supplier` when there is no `SupplierID`, and the reverse; the Available Fields
  for Purchase table of the response requires none. As with the sale, `PurchaseData`,
  `PurchasePostData` and `PurchasePutData` require `Approach` and `Location` always, which every
  example sends, and each supplier field carries `#[RequiredWithout]` naming the other, so a write
  body with neither fails validation before it is sent. `PurchasePostData` has no `ID`, and
  `PurchasePutData` requires it. No operation's prose limits a field to one verb. `Approach` lists
  `INVOICE` and `STOCK`, but the PUT example sends `Stock`, so it stays a string; `Status` is a
  string too, as on the purchase lists (see below). Every response example sends `OrderDate`,
  which no Purchase table lists; `PurchaseData` models it as a date, as the purchase lists'
  `OrderDate` is.
- **Billing and purchase shipping addresses.** Every address table (the Address Model, the Sale
  and Purchase Shipping Address Models, the Supplier/Customer Address Model and Me Address)
  requires `Line1` and `Country`, and every request example sends both. But every `purchase`
  response example sends a `BillingAddress` with both `null`, even after a POST that sent them,
  and the GET example a `ShippingAddress` too; the `sale` PUT response example's billing `Country`
  is `null` as well. One class reads the response and builds the write body, so `AddressData` and
  `PurchaseShippingAddressData` type both as `?string`, for the responses, and mark both
  `#[Required]`, which only a write body checks: a sale's or a purchase's `BillingAddress`, or a
  purchase's `ShippingAddress`, without them is not sent. `SaleShippingAddressData`, which no
  example sends without them, requires them by type, so `AbstractAddressData` declares neither.
  `AddressData` and `SaleShippingAddressData` declare the optional `ID` their tables list;
  `AbstractAddressData` does not, as the Purchase Shipping Address Model has none.
- **Product fields on lines.** "All objects that contain `ProductID` also contain additional
  fields": `ProductLength`, `ProductWidth`, `ProductHeight`, `ProductWeight`, `WeightUnits`,
  `DimensionsUnits` and `ProductCustomField1`–`10`. The product line classes (every child of
  `AbstractLineData`: the sale quote, order and invoice lines and the purchase order and invoice
  lines), the pick and pack line, the purchase stock and unstock lines, the advanced purchase
  stock and put away lines and the inventory movement line take them from the `HasProductFields`
  trait.
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
  which the customer and supplier classes, `ProductData` and `AdditionalAttributeData` take from
  the `HasAdditionalAttributes` trait. The Product table and the Additional Attribute Model give
  each 256 characters, and the trait applies that limit to all of them. The Supplier table's row
  is the same.
- **Customer `TaxNumber`.** The table types it `Int`, but every example has `""` or `null`, so
  it is a nullable string. `Discount` and `CreditLimit` are `int`, as the table types them.
- **Customer `ID` and `Status`.** The table marks `ID` required, but the POST example has none,
  since Cin7 assigns it, so `CustomerPostData` has no `ID`. `Status` is required for POST only,
  so `CustomerData` and `CustomerPutData` leave it optional.
- **Customer addresses and contacts.** The examples also send `CustomerID` on each address
  and contact, and `JobTitle` on each contact; the classes model them. The supplier shares
  them (Supplier Address Model and Supplier Contact Model are one table each, headed
  Supplier/Customer), and its PUT response sends `SupplierID` on each, which the classes model
  too.
- **Supplier `ID` and `Status`.** The table marks `ID` required, but the POST example has none,
  since Cin7 assigns it, so `SupplierPostData` has no `ID` and `SupplierPutData` requires it.
  `Status` is `Yes*` with no condition, unlike the customer's "Required for POST", so every
  supplier class leaves it optional; its values, `Active` and `Deprecated`, are `RecordStatus`.
- **Supplier `TaxNumber`.** As on the customer, the table types it `Int`, but the examples
  send `""` and `null`, so it is a nullable string. `Discount` is `int`, as the table types it.
- **Supplier `LastModifiedOn`.** The table types it `String` and does not mark it read-only,
  but it is the date of the last change, which Cin7 stamps (the customer's table marks the same
  field `DateTime`, read-only), and no request example sends it. It is on `SupplierData` alone,
  with `#[DateTime]`, and `PostSupplier` and `PutSupplier` leave it out of an array body.
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
- **Payment `Type`.** The Sale Payment Line Partial table and the Available Fields for Purchase
  Payments table list `PREPAYMENT`, `PAYMENT` and `REFUND`; every `sale/payment` and
  `purchase/payment` example sends `Payment` or `Refund`, and the notes write `Prepayment`. The
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
- **Line `ProductID` and `SKU`.** Every priced line table marks them `Yes*`, required when
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
- **Auto-generated numbers.** The sale invoice and credit note POST tables have no
  `InvoiceNumber` or `CreditNoteNumber` (Cin7 generates them), so `SaleInvoicePostData` and
  `SaleCreditNotePostData` leave them out. The purchase and advanced purchase tables list them and
  their POST examples send them, so those POST bodies take `InvoiceNumber` and require
  `CreditNoteNumber` (see Purchase Invoice and Purchase Credit Note below).
- **Purchase payment fields per verb.** The Available Fields for Purchase Payments table has one
  Required column, and its notes say which verb takes a field: `ID` is for PUT, `Type` and
  `DepositID` are for POST, and a PUT of a payment taken from a deposit takes no `Amount` or
  `Account`. So `PurchasePaymentPostData` has no `ID`, and `PurchasePaymentPutData` requires `ID`,
  has no `Type` or `DepositID`, and leaves `Amount` and `Account` optional. `TaskID`, `DatePaid`
  and `CurrencyRate`, which the table requires for every verb, stay required on every class, where
  `SalePaymentPutData` requires only `ID` (the sale's `TaskID` is POST-only, and its `CurrencyRate`
  is ignored for a payment from a credit). `ID` is a bare `Yes*`, so `PurchasePaymentData` leaves
  it optional.
- **Purchase payment `Type` on PUT.** The PUT example sends `"Type": "Payment"`, which the table
  makes POST-only. `PutPurchasePayment` leaves `Type` and `DepositID` out of the body, as
  `PostPurchasePayment` leaves out `ID`, and the catalogue round-trips the PUT example through
  `PurchasePaymentPutData` without its `Type`.
- **Purchase payment `DateCreated`.** The table does not mark it read-only, but it is the date Cin7
  stamps on the payment record: the POST response's `DateCreated` is not the one its request sent.
  The POST and PUT examples send it, so the classes model it and the requests leave it out of the
  body.
- **Purchase manual journal.** As with the sale's, the Available field for Purchase Manual Journal
  table of `purchase/manualJournal` adds the purchase's `TaskID` to the Purchase Manual Journal
  Model a purchase embeds, so `PurchaseManualJournalData` serves both, with `TaskID` optional.
  `PurchaseManualJournalPostData` requires it and limits `Status` to `DRAFT` and `AUTHORISED`. A
  voided purchase's example sends `VOIDED`, which the tables do not list; `Status` is the larger
  `TaskStatus`, which has it. The line tables agree but for the purchase's read-only `IsSystem`, so
  `SaleManualJournalLineData` and `PurchaseManualJournalLineData` share
  `AbstractManualJournalLineData`; the journals key on `SaleID` and `TaskID` and carry different
  line models, so they keep a parent per family. The POST example sends `IsSystem`, so
  `PurchaseManualJournalLineData` models it and `PostPurchaseManualJournal` leaves it out of the
  body. The reference marks the endpoint deprecated (simple purchases only).
- **Advanced purchase stock received.** The Available Fields for Purchase Stock Received table of
  `advanced-purchase/stock` is the body of its POST and PUT, `{PurchaseID, TaskID, Status, Lines}`,
  and adds `PurchaseID` to the Advanced Purchase Stock Model; the two share a name, so
  `AdvancedPurchaseStockData` is both, with `PurchaseID` optional and `TaskID` required, as the
  model requires it. Every action answers with `{PurchaseID, StockReceiving}`, which only the
  examples show: it is `AdvancedPurchaseStocksData`, a keyed envelope named in the plural, with
  `StockReceiving` optional. The table and the model limit `Status` to `DRAFT` and `AUTHORISED` on
  a write (the model says POST, the table POST and PUT), so both body classes carry
  `#[In(TaskStatus::Draft, TaskStatus::Authorised)]`; the model's `Length` of 50 for `Status` does
  not apply to the `TaskStatus` enum.
- **Advanced purchase stock `TaskID` on PUT.** The table marks `TaskID` optional for every verb. A
  POST without it, or with the empty GUID, creates a new stock receiving task, so
  `AdvancedPurchaseStockPostData` leaves it optional; a PUT overwrites the task it names, so
  `AdvancedPurchaseStockPutData` requires it, as every PUT body requires its identifier.
- **Advanced purchase stock lines.** The Advanced Purchase Stock Line Model marks `ProductID` and
  `SKU` `Yes*` with no condition, so `AdvancedPurchaseStockLineData` leaves both optional.
  `Name` and `Received` are read-only, but the POST and PUT examples send them, so the class models
  them and the write requests leave them out of the body. `Quantity`'s "minimal value is 1" is not
  checked, as no other quantity is.
- **Advanced purchase manual journals.** The Available field for Purchase Manual Journal table of
  `advanced-purchase/manualJournal` is the `{PurchaseID, ManualJournals}` envelope its GET and POST
  answer with, `AdvancedPurchaseManualJournalsData`, and requires both fields; each journal is the
  Advanced purchase manual journal partial model, `AdvancedPurchasePartialManualJournalData`. The
  POST body is neither: its example sends `{PurchaseID, TaskID, Status, Lines}`, the partial model
  plus the envelope's `PurchaseID`, and the partial model limits `Status` to `DRAFT` and
  `AUTHORISED` for POST, so `AdvancedPurchasePartialManualJournalPostData` requires `PurchaseID`
  and `TaskID` and carries `#[In(TaskStatus::Draft, TaskStatus::Authorised)]`. The partial model
  lists `NOT AVAILABLE`, `DRAFT` and `AUTHORISED`, as the purchase's does, so `Status` is the
  larger `TaskStatus` there too. The partial model's fields agree with the Purchase Manual Journal
  Model's (a required `Status`, optional `Lines` of the Purchase Manual Journal Line Model), so both
  journals extend `AbstractPurchaseManualJournalData`, which moved from
  `src/Data/Purchase/ManualJournal/` to `src/Data/` since its children span the purchase and the
  advanced purchase. The POST example sends the read-only `IsSystem` on its line, so
  `PostAdvancedPurchaseManualJournal` leaves `Lines.*.IsSystem` out of the body, as
  `PostPurchaseManualJournal` does.
- **Advanced purchase invoice.** The Available Fields for Purchase Invoice table of
  `advanced-purchase/invoice` is the `{PurchaseID, Invoices}` envelope every action answers with,
  `AdvancedPurchaseInvoicesData`, which requires both. Its `Invoices` link reads "[] Advanced
  Purchase Invoice Model" but points at the Advanced purchase invoice partial model, which the
  examples follow: each invoice is an `AdvancedPurchasePartialInvoiceData`, with the partial
  model's `TaskID`, `CombineAdditionalCharges`, `InvoiceTotalAmount` and `InvoiceTotalTaxAmount`,
  not the Advanced Purchase Invoice Model's `Payments` and `Paid` (that model is an advanced
  purchase's `Invoice`, `AdvancedPurchaseInvoiceData`). The partial model has the fields of the purchase
  invoice's table, so both extend `AbstractPurchaseInvoiceData`, which moved to `src/Data/` as its
  children now span the two families. The POST example is not the envelope: it sends the partial
  model's fields with the `PurchaseID` beside them, so the POST body is
  `AdvancedPurchasePartialInvoicePostData`, which requires `PurchaseID` (the envelope table
  requires it), `TaskID` and `CombineAdditionalCharges` (the partial model requires them; unlike
  `advanced-purchase/stock`, the reference documents no POST that creates a task without a
  `TaskID`), limits `Status` to `DRAFT` and `AUTHORISED`, and takes the totals the notes mark
  "Not required for POST" as optional. `InvoiceNumber` is auto-generated, but the partial model
  lists it and the POST example sends it, so the POST body takes it, as the purchase invoice's
  does. The lines and charges are the purchase invoice's: as on every line class, a line's
  `ProductID` and `SKU` (`Yes*`) stay required.
- **Advanced purchase put away.** The Available Fields for Purchase Put Away table of
  `advanced-purchase/put-away` is the body of its POST, `{PurchaseID, TaskID, Status, Lines}`, and
  adds `PurchaseID` to the Advanced Purchase Put Away Model; the two share a name, so
  `AdvancedPurchasePutAwayData` is both, with `PurchaseID` optional and `TaskID` required, as the
  model requires it. GET and POST answer with `{PurchaseID, PutAway}`, which only the examples
  show: it is `AdvancedPurchasePutAwaysData`, a keyed envelope named in the plural, with `PutAway`
  optional. The POST body, `AdvancedPurchasePutAwayPostData`, requires `PurchaseID` and leaves
  `TaskID` optional, since a POST without it, or with the empty GUID, creates a new task. The table
  and the model limit `Status` to `DRAFT` and `AUTHORISED` on a POST, so the POST body carries
  `#[In(TaskStatus::Draft, TaskStatus::Authorised)]`; the table's further rule, that only
  `AUTHORISED` is taken once the invoice lines match the receiving, depends on the purchase and is
  left to Cin7. The model's `Length` of 50 for `Status` does not apply to the `TaskStatus` enum.
  The put away has no PUT or DELETE.
- **Advanced purchase put away lines.** The Advanced Purchase Put Away Line Model marks
  `ProductID` and `SKU` `Yes*` with no condition, so `AdvancedPurchasePutAwayLineData` leaves both
  optional. It marks `Location` and `LocationID` `Yes*` too, each "required if" the other is empty,
  so a line needs one of them: each carries `#[RequiredWithout]` naming the other. `Name` and
  `Received` are read-only, but the POST example sends them, so the class models them and
  `PostAdvancedPurchasePutAway` leaves them out of the body. `Quantity`'s "minimal value is 1" is
  not checked, as no other quantity is.
- **Put away and stock received.** The Advanced Purchase Put Away Line Model repeats the Purchase
  Stock Line Model field for field. The Advanced Purchase Stock Line Model differs only in leaving
  `Location` and `LocationID` optional and in not listing `CardID`, which the `advanced-purchase`
  examples send. So the three line classes, `PurchaseStockLineData`, `AdvancedPurchaseStockLineData`
  and `AdvancedPurchasePutAwayLineData`, share `AbstractPurchaseStockLineData`: `Date` and
  `Quantity` required, the other fields, `CardID` and the product fields optional. Each declares its
  own `Location` and `LocationID`, with `#[RequiredWithout]` on the purchase stock and put away
  lines and without it on the advanced stock line. Each task class types its own `Lines`, and the
  put away task classes share no parent with the stock received ones.
- **Advanced purchase payments.** `advanced-purchase/payment` documents the Available Fields for
  Purchase Payments table of `purchase/payment` again, field for field, so its classes split by
  verb the same way and share `AbstractPurchasePaymentData` with the simple purchase's, in
  `src/Data/` since its children span both families: `AdvancedPurchasePaymentPostData` has no
  `ID`, and `AdvancedPurchasePaymentPutData` requires `ID`, has no `Type` or `DepositID`, and leaves
  `Amount` and `Account` optional. `Type` stays a string, as the examples send `Payment` and
  `Refund`. As on `purchase/payment`, the PUT example sends the POST-only `Type` and both write
  examples the `DateCreated` Cin7 stamps, so `PostAdvancedPurchasePayment` leaves out `ID` and
  `DateCreated`, `PutAdvancedPurchasePayment` leaves out `Type`, `DepositID` and `DateCreated`, and
  the catalogue round-trips the PUT example through `AdvancedPurchasePaymentPutData` without its
  `Type`. Every response example adds the advanced purchase's `PurchaseID`, which the table does
  not list; `AdvancedPurchasePaymentData` models it as an optional GUID. The GET's `PurchaseID`,
  `OrderNumber`, `InvoiceNumber` and `CreditNoteNumber` are all optional. The DELETE is documented
  on `/purchase/payment`, so the resource's `delete()` sends `DeletePurchasePayment`.
- **Purchase Invoice.** The reference documents the model twice: the Purchase Invoice Model a
  purchase embeds as its `Invoice`, with `Payments` (Sale Payment Line Model) and `Paid`, and the
  Available Fields for Purchase Invoice table of `purchase/invoice`, which adds `TaskID`,
  `CombineAdditionalCharges`, `InvoiceTotalAmount` and `InvoiceTotalTaxAmount`. `PurchaseInvoiceData`
  carries the union, with the fields only one of them has optional. `PurchaseInvoicePostData`
  follows the table: it requires `TaskID` and `CombineAdditionalCharges`, limits `Status` to
  `DRAFT` and `AUTHORISED`, takes the totals the notes mark "Not required for POST" as optional,
  and has no `Payments` or `Paid`. Its notes call `InvoiceNumber` auto-generated, but, unlike the
  sale invoice's POST table, this table lists it and the POST example sends it, so the POST body
  takes it. Both tables require `InvoiceDueDate`, but the `purchase` POST, PUT and DELETE examples
  embed an invoice that is `DRAFT` or `VOIDED` with a `null` one: `PurchaseInvoiceData` leaves it
  optional, and `PurchaseInvoicePostData`, `AdvancedPurchasePartialInvoiceData` and
  `AdvancedPurchasePartialInvoicePostData` require it, so each declares its own and
  `AbstractPurchaseInvoiceData` takes only `Status` and `Lines`, as the purchase order classes each
  declare their `Memo`; each declares `InvoiceDate` too, which the advanced purchase's embedded
  invoice leaves optional (see below). The four `purchase` examples also send the invoice's
  number under the misspelt `InvocieNumber`, where `purchase/invoice` sends `InvoiceNumber`; as with
  the money task's `SupplierCustomer`, the wire key is modelled, so `PurchaseInvoiceData` takes an
  optional `InvocieNumber` beside the model's `InvoiceNumber`. One class reads both the
  `purchase/invoice` invoice and a purchase's `Invoice`, as the model name is one.
- **Purchase Credit Note.** The reference documents the model twice: the Purchase Credit Note
  Model a purchase embeds as its `CreditNote`, with `Refunds` (Sale Payment Line Model), and the
  Available Fields for Purchase Credit Note table of `purchase/creditnote`, which adds `TaskID` and
  `CombineAdditionalCharges`. `PurchaseCreditNoteData` carries the union, with the fields only one
  of them has optional. `PurchaseCreditNotePostData` follows the table: it requires `TaskID` and
  `CombineAdditionalCharges`, limits `Status` to `DRAFT` and `AUTHORISED`, takes the totals the
  notes mark "Not required for POST" as optional, and has no `Refunds`. Unlike the sale credit
  note's POST table, both tables require `CreditNoteNumber`, and the POST example sends it, so
  every class requires it. Both tables type `Unstock` as a list of Purchase Unstock Line Models,
  and the `purchase/creditnote` examples send a list, but the four `purchase` examples embed it as
  an object, `{Status, Lines}`, and three of them send a `null` `CreditNoteDate`.
  `PurchaseCreditNoteData` follows the tables, so it does not read the embedded credit notes: a
  purchase's `CreditNote` is `SimplePurchaseCreditNoteData` (see below).
- **A simple purchase's credit note.** The four `purchase` examples embed the credit note in a
  shape the `purchase/creditnote` tables and examples do not have: its `Unstock` is an object,
  `{Status, Lines}`, not a list of lines, and a credit note that is `NOT AVAILABLE` or `VOIDED`
  has a `null` `CreditNoteDate` and a `CreditNoteNumber` of `""`. `PurchaseCreditNoteData` keeps
  its tables' rules, so `PurchaseData` reads it as `SimplePurchaseCreditNoteData`, the credit note
  as a simple purchase embeds it: the `CreditNoteDate` the examples send as `null` is optional,
  the other fields the model table requires stay required, and `Unstock` is a
  `PurchaseUnStockData`, a `TaskStatus` `Status` and `Lines` of `PurchaseUnStockLineData`, as the
  Purchase Stock Model is the stock received's. It needs a class of its own, as its `Unstock` has
  another type. The embedded `Invoice`, `Order`, `StockReceived` and `ManualJournals` are the
  sub-paths' `PurchaseInvoiceData` (with an optional `InvoiceDueDate`, see above),
  `PurchaseOrderData` (with an optional `Memo`, see above), `PurchaseStockData` and
  `PurchaseManualJournalData`, whose `TaskStatus` has the `VOIDED` a voided purchase's journal
  sends.
- **Purchase unstock line.** The Purchase Unstock Line Model marks `ProductID`, `SKU`, `Name`,
  `Location`, `BatchSN` and `ExpiryDate` read-only, but the `purchase/creditnote` POST example
  sends them. `PurchaseUnStockLineData` models them, and `PostPurchaseCreditNote` leaves them out
  of the body, which keeps the stock batch (`CardID`), `Date` and `Quantity`. The line carries a
  `ProductID`, so it takes the product fields too (`HasProductFields`).
- **Advanced purchase credit note.** The Available Fields for Purchase Credit Note table of
  `advanced-purchase/creditnote` is the `{PurchaseID, CreditNotes}` envelope every action answers
  with, `AdvancedPurchaseCreditNotesData`, a keyed envelope named in the plural; the table requires
  both fields. Its `CreditNotes` are the Advanced purchase credit note partial model,
  `AdvancedPurchasePartialCreditNoteData`, whose fields and requirements are the purchase credit
  note table's plus a required `CreditNoteInvoiceNumber`, so it extends
  `AbstractPurchaseCreditNoteData`, which now spans both families and so moved to the `src/Data/`
  root. The table names the envelope's list "[] Advanced Purchase Credit Note Model" but links the
  partial model; the Advanced Purchase Credit Note Model an advanced purchase embeds as its
  `CreditNote` (with `Refunds`) is a model of its own, `AdvancedPurchaseCreditNoteData`, which comes
  with the `advanced-purchase` resource. No table documents the POST body: the example sends one
  partial credit note flattened with the envelope's `PurchaseID`, so
  `AdvancedPurchasePartialCreditNotePostData` is the partial model plus a required `PurchaseID`
  (the envelope table requires it), with `Status` limited to `DRAFT` and `AUTHORISED` and the
  totals, "Not required for POST", optional. The table requires `TaskID`, and the example sends
  the empty GUID to create a credit note, so the POST body requires it. The POST example sends the
  unstock lines' read-only fields, so `PostAdvancedPurchaseCreditNote` leaves them out as
  `PostPurchaseCreditNote` does. The DELETE documents only `TaskID` ("ID of Credit Note Purchase
  to Void"), and no `Void` flag, unlike the stock received's and the sale credit note's:
  `DeleteAdvancedPurchaseCreditNote` takes only `taskId`, and its example answers with the credit
  note `VOIDED`.
- **Advanced purchase.** `advanced-purchase` documents an Available Fields for Purchase table and
  Purchase POST/PUT Attributes of its own, which agree with the `purchase` tables field for field
  (types, lengths, the required `Approach` and `Location`, the `ID` for PUT, `Supplier` or
  `SupplierID`), so `AdvancedPurchaseData`, `AdvancedPurchasePostData` and `AdvancedPurchasePutData`
  extend `AbstractPurchaseData`, which moved from `src/Data/Purchase/` to `src/Data/` since its
  children span both families. The tables differ in what the advanced purchase adds, which its
  classes declare: the response's `OrderDate` (typed `DateTime`, so `#[DateTime]`, where
  `PurchaseData` models the `OrderDate` no `purchase` table lists as a date),
  `CombinedReceivingStatus`, `CombinedInvoiceStatus`, `CombinedPaymentStatus` (the purchase lists'
  `ReceivingStatus`, `InvoicingStatus` and `PurchasePaymentStatus`, whose values these are;
  `CombinedInvoiceStatus`'s Length of 20 is shorter than its `PARTIALLY INVOICED / CREDITED`),
  `Type` (`PurchaseType`, which has its three values) and `IsServiceOnly`; the write bodies'
  `IsServiceOnly`, and the POST-only `PurchaseType` (`Simple` or `Advanced`, the `ProcessType` the
  sale's `SaleType` is), which `AdvancedPurchasePutData` does not take and `PutAdvancedPurchase`
  leaves out of an array body; and the embedded documents, which are lists of the advanced
  purchase's models (see below) where a simple purchase has one object of each. Both POST/PUT tables
  require `Approach`, but the `advanced-purchase` PUT example sends none (the response keeps the
  purchase's), so `Approach` moved off `AbstractPurchaseData`'s constructor, which takes only
  `Location`: every child declares it, `AdvancedPurchasePutData` as optional and the others as
  required, after their own required fields, as the order classes declare `Memo`. `Status` and
  `Approach` stay strings, as on `PurchaseData` (the POST example sends `Stock`). The POST and PUT
  examples send `Supplier` twice, `"Test Supplier"` and then `"ABPA"`; a JSON object keeps the last,
  so the fixtures carry `"ABPA"`, though the responses answer `"Test Supplier"`. `DELETE` defaults
  `Void` to `false`, which, as on `purchase`, is left to Cin7 when `void` is not given.
- **An advanced purchase's documents.** `AdvancedPurchaseData`'s `StockReceived`, `PutAway` and
  `ManualJournals` are lists of the sub-paths' `AdvancedPurchaseStockData` and
  `AdvancedPurchasePutAwayData` and of `AdvancedPurchaseManualJournalData` (Advanced Purchase Manual
  Journal Model, which extends `AbstractPurchaseManualJournalData` as the partial journal does);
  `Invoice` and `CreditNote` are lists of `AdvancedPurchaseInvoiceData` and
  `AdvancedPurchaseCreditNoteData` (the Advanced Purchase Invoice and Credit Note Models), which
  extend `AbstractPurchaseInvoiceData` and `AbstractPurchaseCreditNoteData`, as their fields are the
  purchase invoice's and credit note's, with the `TaskID` both require. Their `Payments` and
  `Refunds` are the Purchase Payment Line Model, `PurchasePaymentLineData` (in
  `src/Data/Purchase/Payment/`, the folder of the path it is named for): the Sale Payment Line
  Model's fields with `PurchaseID` and `TaskID`, so it extends `AbstractSalePaymentLineData`. The
  models require `InvoiceDate`, `InvoiceDueDate` and `CreditNoteDate`, but the POST, PUT and DELETE
  examples embed an invoice and a credit note that are `NOT AVAILABLE` with the three dates `null`,
  so those are optional on these two classes, and `AbstractPurchaseInvoiceData` and
  `AbstractPurchaseCreditNoteData` no longer take `InvoiceDate` and `CreditNoteDate`: each child
  declares them, and the other children still require them, after their own required fields. The
  examples also send keys the embedding models do not list: an `InvoicingAndReceivingNumber` (an
  `int`) on every stock received, put away, invoice and manual journal, which no model lists; a
  `CreditNoteInvoiceNumber` on every credit note, which only the Advanced purchase credit note
  partial model lists (and requires); the stock batch's `CardID` on the stock received lines, which
  the put away line model lists but the stock line model lacks; and, on the stock received and put
  away lines, the product fields of the page's Additional fields table ("All Objects that contain
  field 'ProductID', also contain additional fields"). The classes model them, optional:
  `AdvancedPurchaseStockData`, `AdvancedPurchasePutAwayData`, `AdvancedPurchaseInvoiceData` and
  `AdvancedPurchaseManualJournalData` take `InvoicingAndReceivingNumber`,
  `AdvancedPurchaseCreditNoteData` takes `CreditNoteInvoiceNumber`, with the partial model's length
  (`#[Max(50)]`), and `AdvancedPurchaseStockLineData` and `AdvancedPurchasePutAwayLineData` take
  `CardID` and the product fields from `AbstractPurchaseStockLineData`.
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
- **Purchase List and Purchase Credit Note List.** The two tables are one, field for field, so
  `PurchaseListData` and `PurchaseCreditNoteListData` share `AbstractPurchaseListData`, which holds
  every field. Only the values listed for `Type` differ: the credit note list adds
  `Purchase Credit Note` and `Credit Note` to `Simple Purchase`, `Advanced Purchase` and
  `Service Purchase`, and both rows take `PurchaseType`, which has all five, as both sale lists take
  `SaleType`. The credit note list's example returns its rows under `PurchaseList`, as
  `purchaseList` does, so `GetPurchaseCreditNoteList` reads that key. Both tables type `Supplier` as
  `Date`; it is the supplier's name, a string of 256.
- **Purchase list `Status`.** Both tables point `Status` to the Available Purchase Statuses
  (`DRAFT` to `COMPLETED`), but the credit note list's example sends
  `COMPLETED / CREDIT NOTE CLOSED`, which that list does not have. The list is not closed, so
  `Status` stays a string on both rows, and both lists' `status` filter is a string.
- **Purchase list statuses.** `OrderStatus`, `StockReceivedStatus`, `UnstockStatus` and
  `CreditNoteStatus` list `VOIDED`, `DRAFT`, `AUTHORISED` and `NOT AVAILABLE`, and are `TaskStatus`;
  the `OrderStatus` row misses a backtick, which garbles all but `VOIDED`. `InvoiceStatus` lists the
  same four, but the examples send `PAID`, an invoice status, so it is `InvoiceStatus`, which has all
  five. The notes of `UnstockStatus` and `InvoiceStatus` are swapped ("Invoice status" and "Purchase
  unstock status"); each key is the status it names. The combined statuses are `ReceivingStatus`,
  `InvoicingStatus` and `PurchasePaymentStatus` (the sale's `SalePaymentStatus` has `NOT REFUNDED`
  where a purchase has `OVERPAID / CREDITED`); `CombinedInvoiceStatus`'s Length of 20 is shorter
  than its `PARTIALLY INVOICED / CREDITED`, and an enum carries no length rule. The filters take the
  same enums; `RestockReceivedStatus` filters by `StockReceivedStatus`.
- **Purchase list `LastUpdatedDate`.** Both list tables type it `Date`, but its notes say "date and
  time", the purchase tables type the same field `DateTime`, and the examples send a time
  (`2018-04-19T04:03:06.52Z`), so it carries `#[DateTime]`.
- **Sale quote and manual journal.** As with the order, the Sale Quote and Sale Manual Journal
  tables of `sale/quote` and `sale/manualJournal` add `SaleID` (and the quote
  `CombineAdditionalCharges`) to the models a sale embeds, so `SaleQuoteData` and
  `SaleManualJournalData` serve both, with those optional. Their POST bodies require them, and
  limit `Status` to `DRAFT` and `AUTHORISED`; the quote's POST requires no totals ("Not required
  for POST"), and its example sends none.
- **Sale attachment delete.** The reference marks the `ID` of `DELETE sale/attachment` optional;
  a delete names what it deletes, so `DeleteSaleAttachment` requires it. The POST example's base64
  `Content` is a 62 KB image; the fixture keeps its first 32 characters.
- **Purchase attachment.** As with the sale's, `DeletePurchaseAttachment` requires the `ID` the
  reference marks optional, and the POST example's `Content` (the sale's image) is cut to its first
  32 characters in the fixture. The tables key the purchase as `PurchaseID` in the POST body and as
  `TaskID` in the response, and the classes follow them. The note on the GET's `TaskID` says it
  returns payment info, copied from `purchase/payment`; the examples return the attachments. The
  POST example's unquoted keys are quoted in the fixture.
- **ME value lists in the Required column.** The ME table writes the values of
  `TaxCalculationMethod` (`Row Total`, `Total`) and `DiscountRule` (`Discount`, `Price`) in its
  Required column, and the Rounding Table its `AdjustmentRule` letters (`N`, `S`, `A`). They are
  value lists, not requirements: the fields are optional, typed `TaxCalculationMethod`,
  `DiscountRule` and `AdjustmentRule`. The product's `DiscountRule`, the name of a discount, is
  another field, and stays a string.
- **Units of weight and length.** The Dimension Unit Available Values give each unit an
  abbreviation and a name. The abbreviation is the wire value, as the `me` example's `g` and `cm`
  show, so `MeData`'s `DefaultWeightUnits` and `DefaultDimensionsUnits` are `WeightUnit` and
  `DimensionUnit`, whose cases take the names, spelt correctly: the table's `ounces`, `miligramm`
  and `kilogramm` are `Ounce`, `Milligram` and `Kilogram`. The product's `WeightUnits` and
  `DimensionsUnits`, and the lines' (`HasProductFields`), were built before these enums and stay
  strings; the sale examples send `""` for a line's units, outside the list.
- **Rounding Table `AdjustmentValue`.** The table types it `String`, but the example sends `0`;
  `RoundingTableData` accepts both (`string|float`).
- **A staff account in the `me` examples.** The `me` example's company name and the
  `me/contacts` examples' e-mail addresses are a Cin7 staff test account's; the fixtures say
  `Example Company` and use `example.com` addresses.
- **Me Address `AddressID` and Me Contact `ContactID`.** The tables mark neither required, but a
  PUT changes the record its ID names, so `MeAddressPutData` and `MeContactPutData` require it;
  the POST classes have none, since Cin7 assigns it, and `MeAddressData` and `MeContactData` leave
  it optional.
- **Me Contact `Type`.** It lists `Billing`, `Business`, `Sale`, `Shipping` and `Employee`. The
  addresses' list is a subset of it, but an address is never a `Sale` or an `Employee` one, so the
  contact's list is an enum of its own, `ContactType`, and the addresses keep `AddressType`.
- **Me Contact `CRMID` and `ReferenceCount`.** The PUT response example carries both, as `null`
  and `0`, and the table lists neither. `MeContactData` models them, `CRMID` as a string and
  `ReferenceCount` as an `int`; the write bodies do not take them.
- **Chart of Accounts `Type`.** The table lists `BANK`, `CURRLIAB`, `LIABILITY`, `TERMLIA`,
  `PAYGLIABILITY`, `SUPERANNUATIONLIABILITY` and `WAGESPAYABLELIABILITY`, but the examples return
  `CURRENT` and `EXPENSE` as well, so `Type` is a string. `Class`, `SystemAccount` and
  `SystemAccountCode` match their lists, so they are the `AccountClass`, `SystemAccount` and
  `SystemAccountCode` enums.
- **Chart of Accounts `ForPayments`.** The table types it `String` and copies `Status`'s note,
  "Account status"; the examples send `false`, so it is a `bool`.
- **Chart of Accounts `Bank` and `BankAccountNumber`.** The table says both are "Only for PUT and
  POST", but every response example carries `BankAccountNumber`, as `null`, so they are on
  `AccountData` too. A write body of a `BANK` account requires both (`#[RequiredIf]`).
- **Bank Accounts `InitialBalance`.** The table types it `String`, but the example sends `0`;
  `BankAccountData` accepts both (`string|float`).
- **Fixed Asset Type `Rate` and `EffectiveLife`.** The table marks both required, but says each is
  "unable to set" when the other is, and the examples send one of them as `null`. Both are
  optional. `AssetAccountCode` is typed `Decimal`, but the examples send `"710"` and the other
  account code is a `String`, so it is a string. `DepreciationExpenseAccountCode` and its read-only
  `DepreciationExpenseAccountName` appear only in the examples, and are modelled.
- **Fixed Asset Type and Payment Term value lists.** `DepreciationMethod`, `AveragingMethod` and
  the payment term's `Method` are the enums `DepreciationMethod`, `AveragingMethod` and
  `PaymentTermMethod`.
- **Journal `Status`.** The three statuses are the `CompletionStatus` the money task and the
  inventory write-off share. `JournalNumber` is read-only, so it is on `JournalData` alone, and
  `Attachments` is on the response only: no request example sends it.
- **Transactions `Type`.** The table's list of thirteen kinds is the `TransactionType` enum; every
  field of `TransactionData` is optional, as the table marks none required.
- **Brand, Product Category and Unit of Measure responses.** Their POST and PUT answer with the
  saved record itself, `{ID, Name}`, where `ref/tax` and the others answer with the list envelope, so
  their `dto()` reads the whole body. The brand POST example's keys are unquoted, and is fixed
  in its fixture.
- **Examples that are not valid JSON** are fixed when they become a fixture in
  `Cin7Payloads`, not copied verbatim. The `supplier` POST example ends in a trailing comma,
  removed in its fixture.

## Customers

`customer` follows the Customer table, with a class per verb because `ID` and `Status` are
required on different verbs. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `CustomerData` (response) | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `ID` |
| `CustomerPostData` | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `Status` |
| `CustomerPutData` | `src/Data/Customer/` | `Name`, `Currency`, `PaymentTerm`, `AccountReceivable`, `RevenueAccount`, `TaxRule`, `ID` |
| `CustomerAddressData` | `src/Data/Other/` | `Line1`, `Country`, `Type` |
| `CustomerContactData` | `src/Data/Other/` | `Name` |
| `ProductPriceData` | `src/Data/Other/` | `Price`; and on a write body `ProductID` or `ProductSKU`, and `CustomerID` or `CustomerName` (`#[RequiredWithout]`) |

`LastModifiedOn` (read-only) and `ChildCustomers` (responses only) are on `CustomerData` alone.
A response missing a required field fails `dto()` with a `CannotCreateData`.

## Suppliers

`supplier` follows the Supplier table, with a class per verb because `ID` is taken by PUT and the
response only. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `SupplierData` (response) | `src/Data/Supplier/` | `Name`, `Currency`, `PaymentTerm`, `AccountPayable`, `TaxRule`, `ID` |
| `SupplierPostData` | `src/Data/Supplier/` | `Name`, `Currency`, `PaymentTerm`, `AccountPayable`, `TaxRule` |
| `SupplierPutData` | `src/Data/Supplier/` | `Name`, `Currency`, `PaymentTerm`, `AccountPayable`, `TaxRule`, `ID` |

Its `Addresses` and `Contacts` are the customer's `CustomerAddressData` and
`CustomerContactData`, from `src/Data/Other/` (see [customers](#customers)). `LastModifiedOn` is
on `SupplierData` alone. A response missing a required field fails `dto()` with a
`CannotCreateData`.

## Me

`me` is the company the API application belongs to and the settings its documents follow, and
`me/addresses` and `me/contacts` its addresses and contacts, each with a class per verb because
`AddressID` and `ContactID` are taken by PUT and the response only. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `MeData` (response) | `src/Data/Me/` | none |
| `RoundingTableData` | `src/Data/Me/` | none |
| `MeAddressData` (response) | `src/Data/Me/Addresses/` | `Line1`, `CitySuburb`, `StateProvince`, `ZipPostCode`, `Country`, `Type` |
| `MeAddressPostData` | `src/Data/Me/Addresses/` | `Line1`, `CitySuburb`, `StateProvince`, `ZipPostCode`, `Country`, `Type` |
| `MeAddressPutData` | `src/Data/Me/Addresses/` | `Line1`, `CitySuburb`, `StateProvince`, `ZipPostCode`, `Country`, `Type`, `AddressID` |
| `MeContactData` (response) | `src/Data/Me/Contacts/` | `Name` |
| `MeContactPostData` | `src/Data/Me/Contacts/` | `Name` |
| `MeContactPutData` | `src/Data/Me/Contacts/` | `Name`, `ContactID` |

The settings with a value list are enums: `DefaultWeightUnits` is a `WeightUnit`,
`DefaultDimensionsUnits` a `DimensionUnit`, `TaxCalculationMethod` a `TaxCalculationMethod`,
`DiscountRule` a `DiscountRule`, and a rounding row's `AdjustmentRule` an `AdjustmentRule` (see
[above](#where-the-references-tables-and-examples-disagree)). `LockDate` and `OpeningBalanceDate`
are dates, which the example sends as `yyyy-MM-ddTHH:mm:ss`. An address's `Type` is the
`AddressType` of the customer and supplier addresses, and a contact's a `ContactType`, which the
contact does not require. `CRMID` and `ReferenceCount` are on `MeContactData` alone. A response
missing a required field fails `dto()` with a `CannotCreateData`.

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

## Accounting

`ref/account` follows the Chart of Accounts table, with a class per verb because `SystemAccount`
and `SystemAccountCode` are read-only for PUT, and `DisplayName`, `OldCode`, `BankAccountId` and
`Currency` read-only on both. `Code` names the account a PUT changes. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AccountData` (response) | `src/Data/Ref/Account/` | `Code`, `Name`, `Type`, `Status` |
| `AccountPostData` | `src/Data/Ref/Account/` | `Code`, `Name`, `Type`, `Status`; and `Bank` and `BankAccountNumber` when `Type` is `BANK` (`#[RequiredIf]`) |
| `AccountPutData` | `src/Data/Ref/Account/` | `Code`, `Name`, `Type`, `Status`; and `Bank` and `BankAccountNumber` when `Type` is `BANK` |

`Class` is an `AccountClass`, and `SystemAccount` and `SystemAccountCode`, which name the same
system account by name and by code, are the `SystemAccount` and `SystemAccountCode` enums. A
response missing a required field fails `dto()` with a `CannotCreateData`.

`ref/account/bank` lists the bank accounts as `BankAccountData`, in `src/Data/Ref/Account/Bank/`,
which requires nothing: each names the account it is linked to by `AccountCode` and `AccountName`.

`ref/brand`, `ref/category` and `ref/unit` are each a name and an ID, with a class per verb because
the ID is taken by PUT and the response only:

| Class | Folder | Required |
|---|---|---|
| `BrandData`, `BrandPostData` | `src/Data/Ref/Brand/` | `Name` |
| `BrandPutData` | `src/Data/Ref/Brand/` | `Name`, `ID` |
| `ProductCategoryData`, `ProductCategoryPostData` | `src/Data/Ref/Category/` | `Name` |
| `ProductCategoryPutData` | `src/Data/Ref/Category/` | `Name`, `ID` |
| `UnitOfMeasureData`, `UnitOfMeasurePostData` | `src/Data/Ref/Unit/` | `Name` |
| `UnitOfMeasurePutData` | `src/Data/Ref/Unit/` | `Name`, `ID` |

`bankTransfer` is the Money Task's twin, with a class per verb because `TaskID` is taken by PUT and
the response only; `TransactionStockLineData` moved to `src/Data/Other/` when it gained a second
family. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `BankTransferData` (response) | `src/Data/BankTransfer/` | `Status`, `FromAccount`, `ToAccount`, `FromAmount`, `ToAmount`, `Date` |
| `BankTransferPostData` | `src/Data/BankTransfer/` | the same |
| `BankTransferPutData` | `src/Data/BankTransfer/` | the same, and `TaskID` |

`journal` has a class per verb because `TaskID` is taken by PUT and the response only. Each class
requires:

| Class | Folder | Required |
|---|---|---|
| `JournalData` (response) | `src/Data/Journal/` | `Status`, `Currency`, `CurrencyConversionRate`, `EffectiveDate` |
| `JournalPostData` | `src/Data/Journal/` | the same |
| `JournalPutData` | `src/Data/Journal/` | the same, and `TaskID` |
| `JournalLineData` | `src/Data/Journal/` | `Debit`, `Credit`, `Amount`, `BaseAmount` |

`TransactionData` is in `src/Data/Transactions/` and requires nothing.

`ref/fixedassettype` and `ref/paymentterm` have a class per verb because the ID is taken by PUT and
the response only:

| Class | Folder | Required |
|---|---|---|
| `FixedAssetTypeData` (response) | `src/Data/Ref/FixedAssetType/` | `Name`, `DepreciationMethod`, `AveragingMethod`, `AssetAccountCode`, `AccumulatedDepreciationAccountCode` |
| `FixedAssetTypePostData` | `src/Data/Ref/FixedAssetType/` | the same |
| `FixedAssetTypePutData` | `src/Data/Ref/FixedAssetType/` | the same, and `FixedAssetTypeID` |
| `PaymentTermData` (response) | `src/Data/Ref/PaymentTerm/` | `Name` |
| `PaymentTermPostData` | `src/Data/Ref/PaymentTerm/` | `Name` |
| `PaymentTermPutData` | `src/Data/Ref/PaymentTerm/` | `Name`, `ID` |

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

## Purchases

`purchase` follows the Available Fields for Purchase table and the Purchase POST/PUT Attributes,
with a class per verb because the POST/PUT table requires `Approach`, `Location` and a supplier,
and `ID` on PUT only (see [above](#where-the-references-tables-and-examples-disagree)). The
reference marks the endpoint deprecated: it supports only simple purchases. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `PurchaseData` (response) | `src/Data/Purchase/` | `Location`, `Approach` |
| `PurchasePostData` | `src/Data/Purchase/` | `Location`, `Approach`; and `Supplier` or `SupplierID` (`#[RequiredWithout]`) |
| `PurchasePutData` | `src/Data/Purchase/` | `Location`, `ID`, `Approach`; and `Supplier` or `SupplierID` (`#[RequiredWithout]`) |
| `PurchaseShippingAddressData` | `src/Data/Other/` | `Line1`, `Country` on a write body (`#[Required]`); a response may send them `null` |
| `SimplePurchaseCreditNoteData` | `src/Data/Purchase/CreditNote/` | `CreditNoteNumber`, `Status`, `Lines`, `Unstock` |
| `PurchaseUnStockData` | `src/Data/Purchase/` | `Status`, `Lines` |

`TaxCalculation` is a `TaxCalculation`, and `Approach` and `Status` are strings. The embedded
invoice is `purchase/invoice`'s `PurchaseInvoiceData` (see below). The credit note's and its
unstock's `Status` is a `TaskStatus`; its lines and charges are the purchase invoice's
`PurchaseInvoiceLineData` and `PurchaseInvoiceAdditionalChargeData`, and its `Refunds` the shared
`SalePaymentLineData`. `PurchaseShippingAddressData` is in `src/Data/Other/`, as the advanced
purchase ships to one too. A response missing a required field fails `dto()` with a
`CannotCreateData`. The fixtures are the reference's six examples, unchanged.

`purchase/order` follows the Purchase Order Model and the Available Fields for Purchase Order
table, with a POST class because POST requires `TaskID` and `CombineAdditionalCharges` but not the
totals (see [above](#where-the-references-tables-and-examples-disagree)). Its lines and
additional charges are the Purchase Order Line and Purchase Additional Charge Models, which the
purchase and the advanced purchase embed too.

| Class | Folder | Required |
|---|---|---|
| `PurchaseOrderData` (response) | `src/Data/Purchase/Order/` | `Status`, `Lines`, `TotalBeforeTax`, `Tax`, `Total` |
| `PurchaseOrderPostData` | `src/Data/Purchase/Order/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `TaskID`, `CombineAdditionalCharges`, `Memo` |
| `PurchaseOrderLineData` | `src/Data/Purchase/Order/` | `ProductID`, `SKU`, `Name`, `Quantity`, `Price`, `Tax`, `TaxRule`, `Total` |
| `PurchaseAdditionalChargeData` | `src/Data/Other/` | `Description`, `Quantity`, `Price`, `Tax`, `TaxRule`, `Total` |

`Status` is a `TaskStatus`. A line also takes `SupplierSKU`, `Comment` and the product fields, and
a charge a `Reference`. `Memo` is optional on the response, since a purchase embeds an order that
is `NOT AVAILABLE` or `VOIDED` without one (see
[above](#where-the-references-tables-and-examples-disagree)).

`purchase/stock` follows the Purchase Stock Model and the Available Fields for Purchase Stock
Received table, with a POST class because POST requires `TaskID` and takes only two of the
statuses (see [above](#where-the-references-tables-and-examples-disagree)). Its lines are the
Purchase Stock Line Model, which a purchase's `StockReceived` embeds too.

| Class | Folder | Required |
|---|---|---|
| `PurchaseStockData` (response) | `src/Data/Purchase/Stock/` | `Status`, `Lines` |
| `PurchaseStockPostData` | `src/Data/Purchase/Stock/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `TaskID` |
| `PurchaseStockLineData` | `src/Data/Purchase/Stock/` | `Date`, `Quantity`; and on a write body `Location` or `LocationID` (`#[RequiredWithout]`) |

`Status` is a `TaskStatus`. A line also takes `ProductID`, `SKU`, `BatchSN`, `SupplierSKU`,
`ExpiryDate`, `CardID` and the product fields, and, on a response, the read-only `Name` and
`Received`. The line class extends `AbstractPurchaseStockLineData`, at the `src/Data/` root, as the
advanced purchase's stock and put away lines do.

`purchase/invoice` follows the Available Fields for Purchase Invoice table and the Purchase
Invoice Model, with a POST class because POST requires `TaskID` and `CombineAdditionalCharges`
and takes only a `DRAFT` or `AUTHORISED` `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). The reference marks the endpoint
deprecated: it supports only simple purchases. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `PurchaseInvoiceData` (response, and a purchase's `Invoice`) | `src/Data/Purchase/Invoice/` | `Status`, `Lines`, `InvoiceDate` |
| `PurchaseInvoicePostData` | `src/Data/Purchase/Invoice/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `TaskID`, `CombineAdditionalCharges`, `InvoiceDate`, `InvoiceDueDate` |
| `PurchaseInvoiceLineData` | `src/Data/Purchase/Invoice/` | `ProductID`, `SKU`, `Name`, `Quantity`, `Price`, `Tax`, `TaxRule`, `Account`, `Total` |
| `PurchaseInvoiceAdditionalChargeData` | `src/Data/Purchase/Invoice/` | `Description`, `Quantity`, `Price`, `Tax`, `TaxRule`, `Account` |

`Status` is an `InvoiceStatus`. `InvoiceDueDate` is optional on the response, since a purchase
embeds a draft or voided invoice without one, and the response also takes the `InvocieNumber` key
those embedded invoices send (see [above](#where-the-references-tables-and-examples-disagree)).
The line and charge classes extend `AbstractLineData` and `AbstractChargeData`, and are in
`src/Data/Purchase/Invoice/`, the folder of the path they are named for, though the purchase and
advanced purchase credit notes and the advanced purchase's invoices use them too. A response
missing a required field fails `dto()` with a `CannotCreateData`.

`purchase/creditnote` follows the Available Fields for Purchase Credit Note table and the
Purchase Credit Note Model, with a POST class because POST requires `TaskID` and
`CombineAdditionalCharges` and takes only a `DRAFT` or `AUTHORISED` `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). The reference marks the endpoint
deprecated: it supports only simple purchases. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `PurchaseCreditNoteData` (response) | `src/Data/Purchase/CreditNote/` | `CreditNoteNumber`, `Status`, `Lines`, `Unstock`, `CreditNoteDate` |
| `PurchaseCreditNotePostData` | `src/Data/Purchase/CreditNote/` | `CreditNoteNumber`, `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `Unstock`, `TaskID`, `CombineAdditionalCharges`, `CreditNoteDate` |
| `PurchaseUnStockLineData` | `src/Data/Other/` | `CardID`, `Quantity` |

`Status` is a `TaskStatus`, whose values are the table's. The lines and additional charges are the
invoice's `PurchaseInvoiceLineData` and `PurchaseInvoiceAdditionalChargeData` (see above), and
`Refunds` the shared `SalePaymentLineData`. `PurchaseUnStockLineData` is in `src/Data/Other/`, as
the advanced purchase's credit notes use it too. A response missing a required field fails `dto()`
with a `CannotCreateData`.

`purchase/payment` follows the Available Fields for Purchase Payments table, with a class per verb
because `ID`, `Type`, `DepositID`, `Amount` and `Account` are taken by different verbs (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `PurchasePaymentData` (response) | `src/Data/Purchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `Type`, `Amount`, `Account` |
| `PurchasePaymentPostData` | `src/Data/Purchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `Type`, `Amount`, `Account` |
| `PurchasePaymentPutData` | `src/Data/Purchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `ID` |

`Type` is a string, and `DepositID`, which takes a payment from a supplier deposit and goes only
with `Type` `Payment`, is on the response and the POST body. `DateCreated` is on every class and
never sent. A response missing a required field fails `dto()` with a `CannotCreateData`.

`purchase/manualJournal` follows the Purchase Manual Journal Model and the table of its path, with a
POST class because only POST requires `TaskID` and limits `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `PurchaseManualJournalData` (response, and a purchase's `ManualJournals`) | `src/Data/Purchase/ManualJournal/` | `Status` |
| `PurchaseManualJournalPostData` | `src/Data/Purchase/ManualJournal/` | `Status` (`DRAFT` or `AUTHORISED`), `TaskID` |
| `PurchaseManualJournalLineData` | `src/Data/Purchase/ManualJournal/` | `Amount`, `Date`, `Debit`, `Credit` |

`PurchaseManualJournalLineData` is the advanced purchase's journal line too. Its `IsSystem` marks a
line Cin7 posted, which cannot be changed or deleted, and is never sent.

`advanced-purchase` follows its Available Fields for Purchase table and Purchase POST/PUT
Attributes, which are the `purchase` tables' with the advanced purchase's own fields, so its
classes extend `AbstractPurchaseData` and split by verb the same way, with a POST class because only
POST takes `PurchaseType` and a PUT class because PUT requires `ID` and, unlike the tables, not
`Approach` (see [above](#where-the-references-tables-and-examples-disagree)). It serves simple,
advanced and service purchases. Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchaseData` (response) | `src/Data/AdvancedPurchase/` | `Location`, `Approach` |
| `AdvancedPurchasePostData` | `src/Data/AdvancedPurchase/` | `Location`, `Approach`; and `Supplier` or `SupplierID` (`#[RequiredWithout]`) |
| `AdvancedPurchasePutData` | `src/Data/AdvancedPurchase/` | `Location`, `ID`; and `Supplier` or `SupplierID` (`#[RequiredWithout]`) |
| `AdvancedPurchaseInvoiceData` | `src/Data/AdvancedPurchase/Invoice/` | `Status`, `Lines`, `TaskID` |
| `AdvancedPurchaseCreditNoteData` | `src/Data/AdvancedPurchase/CreditNote/` | `CreditNoteNumber`, `Status`, `Lines`, `Unstock`, `TaskID` |
| `AdvancedPurchaseManualJournalData` | `src/Data/AdvancedPurchase/ManualJournal/` | `Status`, `TaskID` |
| `PurchasePaymentLineData` | `src/Data/Purchase/Payment/` | none |

`TaxCalculation` is a `TaxCalculation`, `PurchaseType` a `ProcessType`, the combined statuses
`ReceivingStatus`, `InvoicingStatus` and `PurchasePaymentStatus`, and `Type` a `PurchaseType`;
`Approach` and `Status` are strings. The embedded invoice's `Status` is an `InvoiceStatus`, and the
credit note's and the manual journal's a `TaskStatus`; their lines and charges are the purchase
invoice's `PurchaseInvoiceLineData` and `PurchaseInvoiceAdditionalChargeData`, the unstock lines
`PurchaseUnStockLineData`, and the journal lines `PurchaseManualJournalLineData`. The embedded stock
received and put away are the sub-paths' `AdvancedPurchaseStockData` and
`AdvancedPurchasePutAwayData`. A response missing a required field fails `dto()` with a
`CannotCreateData`. The fixtures are the reference's six examples, unchanged but for the duplicate
`Supplier` key the POST and PUT examples send (see
[above](#where-the-references-tables-and-examples-disagree)).

`advanced-purchase/stock` follows the Available Fields for Purchase Stock Received table and the
Advanced Purchase Stock Model, with a class per verb because `PurchaseID`, `TaskID` and the
`Status` values are taken differently (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchaseStocksData` (response) | `src/Data/AdvancedPurchase/Stock/` | `PurchaseID` |
| `AdvancedPurchaseStockData` | `src/Data/AdvancedPurchase/Stock/` | `Status`, `Lines`, `TaskID` |
| `AdvancedPurchaseStockPostData` | `src/Data/AdvancedPurchase/Stock/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `PurchaseID` |
| `AdvancedPurchaseStockPutData` | `src/Data/AdvancedPurchase/Stock/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `PurchaseID`, `TaskID` |
| `AdvancedPurchaseStockLineData` | `src/Data/AdvancedPurchase/Stock/` | `Date`, `Quantity` |

A required `Lines` may be empty: a POST with `Status` `AUTHORISED` and empty `Lines` authorises
the task. The reference's examples need no correction; the fixtures are the six of them,
unchanged.

`advanced-purchase/put-away` follows the Available Fields for Purchase Put Away table and the
Advanced Purchase Put Away Model, with a response class and a POST class because `PurchaseID`,
`TaskID` and the `Status` values are taken differently (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchasePutAwaysData` (response) | `src/Data/AdvancedPurchase/PutAway/` | `PurchaseID` |
| `AdvancedPurchasePutAwayData` | `src/Data/AdvancedPurchase/PutAway/` | `Status`, `Lines`, `TaskID` |
| `AdvancedPurchasePutAwayPostData` | `src/Data/AdvancedPurchase/PutAway/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `PurchaseID` |
| `AdvancedPurchasePutAwayLineData` | `src/Data/AdvancedPurchase/PutAway/` | `Date`, `Quantity`; and `Location` or `LocationID` on a write body (`#[RequiredWithout]`) |

A required `Lines` may be empty: a POST with `Status` `AUTHORISED` and empty `Lines` authorises
the task. The reference's examples need no correction; the fixtures are the three of them,
unchanged.

`advanced-purchase/invoice` follows the Available Fields for Purchase Invoice table and the
Advanced purchase invoice partial model, with a POST class because POST also takes the purchase's
`PurchaseID` and only a `DRAFT` or `AUTHORISED` `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchaseInvoicesData` (response) | `src/Data/AdvancedPurchase/Invoice/` | `PurchaseID`, `Invoices` |
| `AdvancedPurchasePartialInvoiceData` | `src/Data/AdvancedPurchase/Invoice/` | `Status`, `Lines`, `TaskID`, `CombineAdditionalCharges`, `InvoiceDate`, `InvoiceDueDate` |
| `AdvancedPurchasePartialInvoicePostData` | `src/Data/AdvancedPurchase/Invoice/` | `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `PurchaseID`, `TaskID`, `CombineAdditionalCharges`, `InvoiceDate`, `InvoiceDueDate` |

`Status` is an `InvoiceStatus`. `Lines` are `PurchaseInvoiceLineData` and `AdditionalCharges`
`PurchaseInvoiceAdditionalChargeData`, from `src/Data/Purchase/Invoice/`. A required `Lines` may
be empty: a voided invoice answers with none. The reference's examples need no correction; the
fixtures are the four of them, unchanged.

`advanced-purchase/creditnote` follows the Available Fields for Purchase Credit Note table of its
path and the Advanced purchase credit note partial model, with a POST class because the POST body
adds the purchase's `PurchaseID` and takes only a `DRAFT` or `AUTHORISED` `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchaseCreditNotesData` (response) | `src/Data/AdvancedPurchase/CreditNote/` | `PurchaseID`, `CreditNotes` |
| `AdvancedPurchasePartialCreditNoteData` | `src/Data/AdvancedPurchase/CreditNote/` | `CreditNoteNumber`, `Status`, `Lines`, `Unstock`, `TaskID`, `CombineAdditionalCharges`, `CreditNoteInvoiceNumber`, `CreditNoteDate` |
| `AdvancedPurchasePartialCreditNotePostData` | `src/Data/AdvancedPurchase/CreditNote/` | `CreditNoteNumber`, `Status` (`DRAFT` or `AUTHORISED`), `Lines`, `Unstock`, `PurchaseID`, `TaskID`, `CombineAdditionalCharges`, `CreditNoteInvoiceNumber`, `CreditNoteDate` |

`Status` is a `TaskStatus`, and `AbstractPurchaseCreditNoteData`, at the `src/Data/` root, holds the
fields the simple and the advanced purchase's credit notes share. The lines, additional charges and
unstock lines are the purchase credit note's `PurchaseInvoiceLineData`,
`PurchaseInvoiceAdditionalChargeData` and `PurchaseUnStockLineData` (see above). The reference's
examples need no correction; the fixtures are the four of them, unchanged.

`advanced-purchase/payment` follows the same Available Fields for Purchase Payments table, split by
verb as `purchase/payment` is (see [above](#where-the-references-tables-and-examples-disagree)).
Each class requires what its `purchase/payment` twin does:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchasePaymentData` (response) | `src/Data/AdvancedPurchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `Type`, `Amount`, `Account` |
| `AdvancedPurchasePaymentPostData` | `src/Data/AdvancedPurchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `Type`, `Amount`, `Account` |
| `AdvancedPurchasePaymentPutData` | `src/Data/AdvancedPurchase/Payment/` | `TaskID`, `DatePaid`, `CurrencyRate`, `ID` |

The response adds the `PurchaseID` every example carries, as an optional GUID. A payment needs an
authorised invoice, and a refund an authorised credit note. The reference's examples need no
correction; the fixtures are the five of them, unchanged.

`advanced-purchase/manualJournal` follows the Available field for Purchase Manual Journal table and
the Advanced purchase manual journal partial model, with a POST class because POST also takes the
purchase's `PurchaseID` and limits `Status` (see
[above](#where-the-references-tables-and-examples-disagree)). Each class requires:

| Class | Folder | Required |
|---|---|---|
| `AdvancedPurchaseManualJournalsData` (response) | `src/Data/AdvancedPurchase/ManualJournal/` | `PurchaseID`, `ManualJournals` |
| `AdvancedPurchasePartialManualJournalData` | `src/Data/AdvancedPurchase/ManualJournal/` | `Status`, `TaskID` |
| `AdvancedPurchasePartialManualJournalPostData` | `src/Data/AdvancedPurchase/ManualJournal/` | `Status` (`DRAFT` or `AUTHORISED`), `PurchaseID`, `TaskID` |

The lines are the purchase's `PurchaseManualJournalLineData`. A journal's `TaskID` is the purchase
invoice task it belongs to. The reference's examples need no correction; the fixtures are the three
of them, unchanged.
