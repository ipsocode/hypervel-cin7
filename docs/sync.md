# Sync

The sync keeps a copy of Cin7's records in one local table, so an application can list,
search and join them without paging the API each time. A scheduled pull stores each record as
the raw JSON Cin7 returned, keyed by account, module and Cin7 identifier.

It is off by default. Off, the package creates no table, schedules nothing and runs no query.
Turn it on in the environment, then run the migration:

```dotenv
CIN7_SYNC=true
```

```sh
php artisan migrate
php artisan cin7:sync          # the first fill, in the foreground
```

Every key is in [configuration](configuration.md#keys).

## The table

`cin7_sync_payloads` holds every synced record, list rows and full documents alike:

| Column | Holds |
|---|---|
| `account_id` | The Cin7 account the record belongs to, from `cin7.account_id` |
| `module` | The endpoint it came from, spelled as the request path: `customer`, `saleList`, `ref/tax` |
| `cin7_id` | The record's identifier, under the key its module uses (below) |
| `payload` | The record as Cin7 returned it: `jsonb` on PostgreSQL, `json` on MySQL |
| `cin7_modified_at` | Cin7's own modified time for the record, in UTC; null for a reference book |
| `synced_at` | The last pull that returned the record, changed or not |
| `created_at`, `updated_at` | `updated_at` moves only when the record changed |

`(account_id, module, cin7_id)` is unique, and `(account_id, module, cin7_modified_at)` and
`(account_id, module, updated_at)` are indexed, so "changed since" is a query on `updated_at`.
The table lives on `cin7.sync.connection`, the default connection when unset. The migration
loads only while the sync is on, and publishes under `cin7-migrations`:

```sh
php artisan vendor:publish --tag=cin7-migrations
```

Nothing else is stored. The schedule comes from config, a waiting or failed pull is a row in
the framework's `jobs` or `failed_jobs`, and the locks are cache locks. No cursor is kept (see
[a pull](#a-pull)).

## Modules

A module is one case of [`Ipsocode\Cin7\Sync\Module`](../src/Sync/Module.php), named by its
request path. `'*'`, the default for `cin7.sync.modules`, is every module, in this order: what
other records point to comes before the records that point to it.

| Order | Modules | Identifier | Read |
|---|---|---|---|
| 1 | `ref/account` | `Code` | whole |
| 2 | `ref/account/bank` | `AccountID` | whole |
| 3 | `ref/location` | `ID` | whole |
| 4 | `ref/tax`, `ref/paymentterm`, `ref/category`, `ref/brand`, `ref/unit` | `ID` | whole |
| 5 | `ref/carrier` | `CarrierID` | whole |
| 6 | `ref/attributeset` | `ID` | whole |
| 7 | `ref/fixedassettype` | `FixedAssetTypeID` | whole |
| 8 | `customer`, `supplier`, `product` | `ID` | what changed, by `ModifiedSince` and `LastModifiedOn`; deprecated records too |
| 9 | `saleList` | `SaleID` | what changed, by `UpdatedSince`/`UpdatedUntil` and `Updated` |
| 10 | `purchaseList` | `ID` | what changed, by `UpdatedSince`/`UpdatedUntil` and `LastUpdatedDate` |
| 11 | `sale` | `ID` | one `GET sale` per `saleList` row that changed |
| 12 | `advanced-purchase` | `ID` | one `GET advanced-purchase` per `purchaseList` row that changed |

- **Reference books** (`ref/…`) have no since filter and their records no modified time, so a
  pull reads the whole book every time and compares each record with the stored payload. The
  schedule leaves them to the weekly full pull; `cin7:sync` reads them on demand.
- **Documents.** Cin7 has no list of invoices or payments: they exist only inside a sale or a
  purchase. `sale` and `advanced-purchase` store the whole document, one call each, which is
  what makes them searchable locally. Every purchase is read from `advanced-purchase`: the
  reference marks `purchase` deprecated, for simple purchases only, and has `advanced-purchase`
  serve simple, advanced and service purchases alike.
- **Not synced:** price tiers (not a paged list), document templates, product availability (no
  single identifier), and customer credits and supplier deposits (each per customer).

## A pull

[`Synchroniser::pull()`](../src/Sync/Synchroniser.php) pulls one module:

1. It takes a cache lock, `cin7:sync:<account>:<module>`, for up to `sync.timeout` seconds, and
   skips the module if another pull holds it.
2. A module with no rows, a reference book, or a pull asked for as full reads every record. An
   incremental pull asks for what changed since `sync.lookback` minutes ago (a day by
   default), or since the module's newest `synced_at` when that is older, so a gap in the
   schedule is read again. `saleList` and `purchaseList` also send the pull's start as
   `UpdatedUntil`, so the pages stay still while they are walked.
3. It walks the pages one at a time, `sync.limit` records a page, waiting `sync.pause_ms`
   between calls. It does not send pages concurrently: the sync shares the application's
   rate-limit window, and the connector makes every caller wait for capacity rather than fail,
   so a burst would stall the application's own calls.
4. For each page it writes the new and changed records whole and moves `updated_at`. An
   unchanged record (the same modified time, or for a reference book the same payload) has
   only its `synced_at` moved; a full pull refreshes its payload too, without moving
   `updated_at`.
5. A full pull that completes and read something then deletes the module's rows it did not
   see. A pull that fails deletes nothing, and keeps what it wrote; one that began on an empty
   module is undone, so the next pull is a full one again.

No cursor is stored. Reading the look-back window again costs a few pages, and a record whose
modified time has not moved is not rewritten, so a pull that was missed or failed is covered by
the next one with nothing to keep in step. The weekly full pull covers a gap longer than that.

A document module reads the rows of its list that have no document yet, or whose modified
time is later than the document's, oldest change first, at most `sync.documents` a run; the
rest wait for the next run. A document stores its list row's modified time. One that fails stays
pending and the others are still read. A full pull of a document module also deletes the
documents whose list row is gone.

The calls a first fill costs: one per page of each list (500 records a page), plus one per
sale and purchase, at most `sync.documents` of each per run. With the default hourly pull and
250 documents, 6,000 sales take a day to fill.

## The schedule

When the sync is on, the provider registers, through `callAfterResolving(Schedule::class)`:

| Name | When | Pulls |
|---|---|---|
| `cin7:sync` | `sync.cron`, hourly by default | every configured module that reads only what changed: not the reference books |
| `cin7:sync:<module>` | each time in `sync.exceptions` | that module as well, usually more often |
| `cin7:sync:full` | `sync.full`, Sundays at 02:00 by default | every configured module, the reference books included, as a full pull |

```php
'exceptions' => [
    'saleList' => '*/5 * * * *',
    'sale' => '*/5 * * * *',
],
```

A reference book cannot be an exception: it is read whole, so only the full pull and
`cin7:sync` read it, and naming one throws. A `null` time registers no entry, so an application
that wants its own conditions sets `cron` and `full` to `null` and schedules the job itself.

Each entry dispatches [`Ipsocode\Cin7\Sync\Pull`](../src/Sync/Pull.php), a queued job, and runs
on one server. So the framework's own tables do the bookkeeping: a waiting pull is a row in
`jobs`, and one whose three tries ran out is a row in `failed_jobs`, with
`Ipsocode\Cin7\Sync\SyncFailed` naming each module that failed; `queue:retry` runs it again.
The job is unique per account, modules and kind, goes on `sync.queue`, and may run for
`sync.timeout` seconds. A module that fails does not stop the others; the job fails once they
have run.

Running it needs the scheduler (`php artisan schedule:run`) and a queue worker whose
`retry_after` exceeds `sync.timeout`. With the `sync` queue driver the pulls run inside the
scheduler instead.

## The command

```sh
php artisan cin7:sync                       # the modules in cin7.sync.modules
php artisan cin7:sync ref/tax customer      # these only, in this order
php artisan cin7:sync '*' --full            # every module, as a full pull
php artisan cin7:sync --status              # what the table holds; pulls nothing
```

It runs the pull in the foreground and prints, per module, the records read, written,
unchanged and deleted, and the result. It exits 1 if a module failed, a name is unknown, or the
sync is off. It never prints the account ID or the application key.

## Reading the table

[`Ipsocode\Cin7\Sync\Payload`](../src/Sync/Payload.php) is an Eloquent model on the table, with
`payload` cast to an array:

```php
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;

// The connector's account by default; pass an account ID for another.
$changed = Payload::forModule(Module::SaleList)
    ->where('updated_at', '>', now()->subDay())
    ->get();

$sale = Payload::forModule(Module::Sale)->where('cin7_id', $saleId)->sole();
$sale->payload['Invoices'];   // the raw array
$sale->data();                // a SaleData
```

The package does not choose which fields are searched. Index the paths you query in a
migration of your own:

```php
// MySQL: a generated column over the JSON, then an index on it.
$table->string('order_number', 50)->virtualAs("json_unquote(payload->'$.OrderNumber')")->nullable();
$table->index(['module', 'order_number']);
```

```php
// PostgreSQL: an index on the jsonb itself.
$table->index('payload', null, 'gin');
```

The behavior above is pinned by the tests in [`tests/Feature/Sync/`](../tests/Feature/Sync).
