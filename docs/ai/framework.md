# Framework pages

hypervel-cin7 is a Cin7 client on Hypervel's Saloon, with Hypervel data objects for its bodies and an
optional sync on the database, the queue and the scheduler. Before changing a part of `src/`, read
the Hypervel sections listed for it: with the hypervel-porting MCP server's `read_doc` where it is
available, else in `vendor/hypervel/components/src/docs/`.

| Part of `src/` | Read |
|---|---|
| `Cin7Connector.php`, `Cin7Manager.php` | saloon.md › Defining Connectors; saloon.md › Connector Defaults; saloon.md › HTTP Connections |
| `Cin7Connector.php`: the rate limit and the throttling cooldown | saloon.md › Rate Limiting; saloon.md › Server Cooldowns; rate-limiting.md › Defining Rate Limits; rate-limiting.md › Selecting a Store |
| `Requests/`, `PageDefaults.php`, `Support/Jitter.php` | saloon.md › Defining Requests; saloon.md › Request Defaults; saloon.md › Query Parameters; saloon.md › Request Bodies; saloon.md › Error Handling; saloon.md › Retries |
| `Resources/` | saloon.md › Organizing SDKs |
| `Pagination/` | saloon.md › Page Pagination; saloon.md › Pooled Pagination |
| `Data/`, `Attributes/`, `Concerns/`, `Enums/` | data-objects.md › Defaults, Null, and Optional Values; data-objects.md › Abstract Data Objects; data-objects.md › Nested Data Objects; data-objects.md › Date and Time Values; data-objects.md › Backed Enums; data-objects.md › Validation Attributes; data-objects.md › Saloon; saloon.md › Data Objects |
| `Sync/Payload.php`, `Sync/Synchroniser.php`, `Sync/SyncConfig.php` | database.md › Using Multiple Database Connections; queries.md › Joins; queries.md › Upserts; eloquent.md › Eloquent Model Conventions; cache.md › Atomic Locks; helpers.md › Sleep |
| `Sync/Pull.php` | queues.md › Class Structure; queues.md › Unique Jobs; queues.md › Specifying Max Job Attempts / Timeout Values |
| `Cin7ServiceProvider.php` | packages.md › Configuration; packages.md › Migrations; packages.md › Commands; packages.md › "About" Artisan Command; scheduling.md › Scheduling Queued Jobs; scheduling.md › Running Tasks on One Server; migrations.md › Migration Structure |
| `Console/` | artisan.md › Writing Commands; artisan.md › Defining Input Expectations |
| `Testing/Cin7Fake.php` | saloon.md › Faking Responses |

## Default References

- packages.md › Package Discovery
- packages.md › Service Providers
- packages.md › Test State Cleanup
- coroutines.md
