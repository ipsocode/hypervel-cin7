---
name: cin7-models
description: Build the rest of the Cin7 V2 reference in this package, by name and never all at once, from TODO.md's names grouped by reference/<group>/<resource>. Use when asked to implement or update a Cin7 endpoint, model, resource or group by its reference name, or to work an issue that picks groups from TODO.md.
---

# Building the Cin7 reference, by name

`TODO.md` (on `feature/saloon`, deleted before it merges into `main`) lists every resource of the
reference by its anchor, `reference/<group>/<resource>`, the page
`https://dearinventory.docs.apiary.io/#reference/<group>/<resource>`. It ranks the groups High, Medium
or Low, lists the models each resource documents with the class each becomes, and ticks what exists.

## 1. What to build

You are given names, usually from an issue that picks groups from `TODO.md`. A name can be:

- a group: `reference/purchase/**` or `reference/purchase`, meaning each unticked resource in it, in
  `TODO.md`'s order;
- a resource: `reference/purchase/purchase-order`, or its tail `purchase-order`, or its path
  `purchase/order`;
- a model or class: `PurchaseOrderLineModel`, `PurchaseOrderLineData`, `GetPurchaseOrder`. These
  resolve to the resources that document or use them.

Build only what is named. Asked for everything, or for no name, ask which groups to take, and offer
`TODO.md`'s High groups. A resource is the unit: build it, check it, commit it, then take the next.

## 2. Set up once per session

```sh
export CIN7_BLUEPRINT="$SCRATCH/dearinventory.apib"   # any temporary directory
curl -fsSL https://jsapi.apiary.io/apis/dearinventory.apib -o "$CIN7_BLUEPRINT"
S=.github/claude/skills/cin7-models/scripts           # run with the host's python3
python3 $S/names.py list reference/purchase           # the names, with what exists
```

`TODO.md` was written from the blueprint with sha256 `0f497ee8c694`. If
`sha256sum "$CIN7_BLUEPRINT"` differs, the reference has changed: re-read each section you build,
because line numbers and decisions may have moved.

PHP runs only in the CI image, through `ci` (see `CLAUDE.md`); `git` and `python3` run on the host.

## 3. The work order

`python3 $S/names.py show <name>` prints, for each resource:

- its anchor, docs link, path, folder and accessor;
- each operation with its request class, query parameters and examples, and whether the class exists;
- each field table, with every field's type, length, Required value, translated rule, values and
  notes;
- every shared model it uses (the reference's Other Models), with its class, where that class lives
  if it exists, and which resources use it;
- the decision, when the reference is wrong or ambiguous for that resource (`NOTES` in `names.py`).

Read the blueprint section around the cited lines too. Prose under an operation or a table carries
rules the tables do not have, such as "For POST only `DRAFT` and `AUTHORISED`", "required if
`CombineAdditionalCharges` is false", or a field that only the examples send.

`python3 $S/names.py example <path> <VERB> request|response [n]` prints an example as JSON for a
fixture, fixing what the blueprint gets wrong (comments, trailing commas, unquoted keys). Log every
fix it reports in the docs' deviations.

## 4. Rules

They replicate the reference. Where the reference contradicts itself, `NOTES` and section 6 hold
one decision each.

### Names and folders

- A URI segment is PascalCase words: `advanced-purchase` is AdvancedPurchase, `put-away` PutAway,
  `stockTakeList` StockTakeList, and the run-on segments are in `SEGMENTS` in `blueprint.py`. Acronyms
  are words: `Bom`, `Crm`, `Id`.
- A class's folder is its path: `src/Requests/Purchase/Order/`, `src/Data/Purchase/Order/`,
  `src/Resources/Purchase/OrderResource.php`. The tests and fixtures mirror the path:
  `tests/Fixtures/Catalogue/purchase/order.php` and `workbench/fixtures/purchase/order/`.
- `moneyOperation` is the one path named after its model: `MoneyTask` everywhere, URL unchanged
  (`RequestCatalogueTest::PATHS_NAMED_AFTER_THEIR_MODEL`).
- A request is its verb plus its path's segments, without a leading `Ref` or `Reference`:
  `GetPurchaseOrder`, `GetAccountBank`, `DeleteShipZones`. `names.py show` prints each one.
- A model's class is its reference name without `Model`, plus `Data`: `PurchaseOrderLineData`. An
  unanchored table takes its heading (`Available Fields for Purchase Order` is `PurchaseOrderData`),
  singular for one record (`SupplierDepositData`). A keyed envelope such as `{PurchaseID, Invoices}`
  is plural (`AdvancedPurchaseInvoicesData`). A list's row is named after the list
  (`PurchaseListData`). `TABLES` in `names.py` holds the exceptions, and `TODO.md` prints every
  class.
- A model lives in the folder of the path whose name it carries (`PurchaseOrderLineData` in
  `Purchase/Order/`), else in the deepest folder common to the paths that use it. Once two families
  use it, it moves to `src/Data/Other/`, its namespace changes, and the docs follow. Abstract parents
  stay where they are: in their children's common folder, at the `src/Data/` root when they span
  families. Traits go in `src/Concerns/`, attributes in `src/Attributes/` and enums in `src/Enums/`.

### Data classes

- Property names are the wire keys. A required field (`Yes`) has no default. An optional field is
  `?type $Field = null`, and null means the field is left out of the body.
- A GUID is `#[Uuid] string`. A string field carries the table's length as `#[Max(n)]`. A date-time
  carries `#[DateTime]` (`Ipsocode\Cin7\Attributes\DateTime`), and a date `#[Date]`.
- Translate the Required column as `names.py show` reads it:
  - `Yes` means required.
  - "Required for POST/PUT" and "Yes when updating" mean required on that verb's class.
  - "Required if X is empty" means `#[RequiredWithout('X')]`.
  - "Required if Status is …" means `#[RequiredIf('Status', …)]`.
  - A bare `Yes*` is optional, with its condition in the docs.
  - "Not required for POST" in the notes means optional on the POST class.
- A documented closed list of values is a string-backed enum in `src/Enums/`. Its cases are
  PascalCase and its values are the wire strings, as in `case NotAvailable = 'NOT AVAILABLE'`.
  Reuse an enum with the same set, such as `TaskStatus`, `OrderStatus` or `InvoiceStatus`. Make no
  enum where the examples contradict the list. A write-only subset ("For POST only …") is `#[In]` on
  the write class.
- One class serves POST, PUT and the response while their rules agree. When they differ, split by
  verb:
  - `XData` is the response;
  - `XPostData` and `XPutData` are the bodies (PUT needs the ID);
  - an abstract `AbstractXData` holds the shared fields.
  - Its constructor takes the fields every child requires, and each child forwards them first.
  - A body line that differs from the response line is `XLinePostPutData`.
- One class per model name carries the union of the tables that describe it. A field only one table
  has stays optional.
- A field that only prose or examples document is modelled on the class that sends or receives it.
  A read-only field that request examples send is modelled, and the write request `$omit`s it.
- A response class implements `WithResponse`.

### Requests and resources

- Requests extend one of three bases:
  - `ListRequest(?int $page, ?int $limit)`, with `$listKey` and `filters()`, for a paged list. The
    list key comes from the example, which can differ from the path (see `NOTES`).
  - `Cin7Request`, with typed constructor arguments and `defaultQuery()` through `queryValues()`,
    for a keyed GET or DELETE.
  - `WriteRequest(array|Data $body)`, with `$omit`, for POST and PUT.
- Query parameters are typed named arguments, required ones first:
  - a GUID or string is `string`;
  - a flag is `bool`;
  - a date is `DateTimeInterface|string`;
  - a closed list is its enum.
- `python3 $S/requests.py spec.json` writes request classes in these shapes from a JSON list. Copy
  the closest existing class for anything else.
- A resource per path: its methods are the operations (`get`, `post`, `put`, `delete`, and
  `paginate` for a list), and its parent's accessor is the last segment in camelCase
  (`$cin7->purchase()->order()`). A top-level accessor is on `Cin7Connector`. A grouping segment such
  as `ref`, `reference` or `crm` works like `RefResource`. A single-operation action sub-path
  (`production/order/release`) is a method on its parent's resource.

### Tests and fixtures

- **Fixtures:** one JSON file per example, `workbench/fixtures/<path>/<verb>.<request|response>.json`,
  read with `Cin7Payloads::load('<path>', '<verb>.<kind>')`. Never put a real credential, account
  or customer in one.
- **Catalogue rows:** `tests/Fixtures/Catalogue/<path>.php` returns rows by kind. A key two files
  share throws. The kinds are:
  - `requests`: class, arguments, method, path, query and body; for a data-object body, add a
    `with data` row;
  - `resources`: every public resource method;
  - `dtos`: a fixture round-trips through `createDtoFromResponse`;
  - `bodies`: a write fixture round-trips through its body class;
  - `missing`: each required field, left out, fails;
  - `required`: a class's required fields, in its constructor's order, the parent's forwarded ones
    first;
  - `omitted`: a request's body without its `$omit` fields.
- **Body key order:** an expected body follows `toArray()`. That is the child's own promoted
  properties, then the parent's declared properties, then the parent's promoted constructor
  properties, then trait properties.
- **Hard-coded lists to extend:**
  - in `DataCatalogueTest`, the abstract parents (in the asserted order) and the `WithResponse`
    classes;
  - the accessors in `ConnectorResourcesTest`;
  - in `BodyValidationTest`, a case for every new `#[In]` (`postOnlyStatusProvider`),
    `#[RequiredWithout]` (`eitherFieldProvider`) and `#[RequiredIf]` rule.

### Docs

- `docs/data.md` takes the classes by path, the abstract parents and the deviations from the
  reference.
- `docs/requests.md` takes the request classes by folder and their query parameters.
- `docs/resources.md` takes the accessors and their methods.
- Each says what the reference got wrong and what was decided.

## 5. Check, tick, commit

1. Run `ci composer lint:fix`, then `ci composer lint`, `ci composer analyse`,
   `ci composer conventions` and `ci composer test:coverage`. The last must hold 100% line coverage:
   cover a line rather than ignore it.
2. Re-read the diff against `names.py show`: every field, type, length, rule and operation.
3. Tick the resource and its models in `TODO.md`.
4. Commit one resource, or one small group, per commit. The message says what it adds, and names the
   issue as `#<n>` when there is one.
5. Push your branch only after the checks pass.

## 6. Decisions on the reference

`names.py show` prints the one for its resource. Across resources:

- The headings of `moneyOperation` and `bankTransfer` say "Money Task List", copied from the list:
  the models are the money task and the bank transfer.
- Markup prices: the heading says `/ref/markupprices`, the operations `/product/markupprices`.
  Follow the operations: `product()->markupPrices()`.
- CRM task category and workflow: their GET URIs say `/crm/task`. Follow their headings.
- Advanced purchase payment DELETE: its URI is `/purchase/payment`, so it sends
  `DeletePurchasePayment`.
- `production/productionBOM` is documented twice. Its requests are named after the titles:
  `GetProductProductionBom` and `GetProductFamilyProductionBom`.
- Ship zones DELETE: the key is documented as `ShipZoneID ` with a trailing space. Send `ShipZoneID`.
- `crm/workflowstart` takes query parameters only. Keep its misspelt `EnityType` key.
- Webhook payload examples describe incoming events: out of scope.
- Other Models' `PriceTierModel` is the product's `PriceTiers` map, with no class.
- `SupplierAddressModel` and `SupplierContactModel` are already `CustomerAddressData` and
  `CustomerContactData`. The supplier and the CRM lead reuse them from `src/Data/Other/`.
