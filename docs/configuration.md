# Configuration

The package reads its settings from `config/cin7.php`: the Cin7 credentials,
the connector's rate limit, and the throttling retry policy. The service provider
merges the packaged file into `config('cin7')`, so every key below has a value
whether or not you publish your own copy, and it builds the connector from
those values through `config()`, never by reading the environment directly.

## Keys

| Key | Env | Default | Meaning |
|---|---|---|---|
| `account_id` | `CIN7_ACCOUNT_ID` | none | Sent as the `api-auth-accountid` header |
| `application_key` | `CIN7_APPLICATION_KEY` | none | Sent as the `api-auth-applicationkey` header |
| `default` | `CIN7_CONNECTION` | `default` | The [connection](connector.md#named-connections) that `Cin7Connector` injection and `Cin7Manager::connection()` resolve |
| `connections` | none | `[]` | More accounts or API applications by name, each with `account_id`, `application_key` and an optional `rate_limit` block |
| `rate_limit.max` | `CIN7_RATE_MAX` | `60` | Calls allowed per window, per API application; `0` disables the window (the throttling cooldown still applies) |
| `rate_limit.period` | `CIN7_RATE_PERIOD` | `60` | Window length in seconds; `0` also disables the window |
| `rate_limit.store` | `CIN7_RATE_STORE` | none | Rate limiter store; unset falls back to `saloon.rate_limiter.store`, then `rate-limiter.default` (see [connector](connector.md)) |
| `retry.times` | `CIN7_RETRY_TIMES` | `4` | Total attempts on a 429 or 503, not extra ones |
| `retry.delay_ms` | `CIN7_RETRY_DELAY_MS` | `5000` | Milliseconds between attempts |
| `sync.enabled` | `CIN7_SYNC` | `false` | The [sync](sync.md): off, it creates no table, schedules nothing and runs no query |
| `sync.cron` | `CIN7_SYNC_CRON` | `0 * * * *` | When the modules that read only what changed are pulled; `null` schedules none |
| `sync.modules` | none | `'*'` | The modules synced, in order; `'*'`, alone or in a list, is every module in [dependency order](sync.md#modules) |
| `sync.exceptions` | none | `[]` | `module => cron`: a module pulled at a time of its own as well; not a reference book |
| `sync.full` | `CIN7_SYNC_FULL` | `0 2 * * 0` | When every module, the reference books included, is pulled whole; `null` schedules none |
| `sync.lookback` | `CIN7_SYNC_LOOKBACK` | `1440` | Minutes an incremental pull reaches back |
| `sync.limit` | `CIN7_SYNC_LIMIT` | `500` | Records a page, 1 to 1000 |
| `sync.pause_ms` | `CIN7_SYNC_PAUSE_MS` | `1000` | Milliseconds between the sync's own calls |
| `sync.documents` | `CIN7_SYNC_DOCUMENTS` | `250` | Sale and purchase documents read per module per run |
| `sync.queue` | `CIN7_SYNC_QUEUE` | none | The queue the scheduled pulls go on |
| `sync.timeout` | `CIN7_SYNC_TIMEOUT` | `3600` | Seconds a queued pull, and its module lock, may last |
| `sync.connection` | `CIN7_SYNC_CONNECTION` | none | The database connection the table lives on |

The credentials go in your environment:

```dotenv
CIN7_ACCOUNT_ID=
CIN7_APPLICATION_KEY=
```

The top-level `account_id`, `application_key` and `rate_limit` are the implicit
`default` connection, so a single account needs nothing more. A second account,
such as a sandbox, is an entry in `connections`; see
[named connections](connector.md#named-connections).

The `rate_limit` defaults match Cin7's limit of roughly 60 calls per minute per
account. How the limit is enforced, and which stores share it across workers
and servers, is in [connector](connector.md). How the retry
values are applied is in [requests](requests.md#retry-policy).

## Publishing

```sh
php artisan vendor:publish --tag=cin7-config
```

This copies the packaged file to your application's `config/cin7.php`.
Publishing is optional: the packaged defaults are merged in either way.

The provider registers the `cin7-config` publish group only when the
application is running in the console. `publishes()` keeps its paths in a
static array for the life of the process, and only `vendor:publish` reads that
group, so a worker booting to serve requests does not register it.

## When values are read

Nothing in the package calls `env()` or `getenv()` at request time, which would
be unsafe inside a Swoole worker. `env()` appears only in `config/cin7.php`,
which is evaluated when the configuration loads. Everything else goes through
`config()`, at two points:

| Values | Read when | Effect of a later config change |
|---|---|---|
| `account_id`, `application_key`, `rate_limit.*`, `connections.*` | The first time a connection is resolved, by `Cin7Connector` injection or `Cin7Manager::connection()` | None for that worker: each connector is built once per worker |
| `default` | Each time `Cin7Connector` is resolved or `connection()` is called without a name | Picks which connection that resolves; the connectors already built stay |
| `retry.*` | Each time a request is constructed (`new GetCustomer(...)` and the rest) | Applies to requests constructed after the change |
| `sync.enabled` | When the provider boots | None for that worker: the migration and the schedule are registered at boot |
| the other `sync.*` | Each time a pull, the schedule or the job reads them | Applies from the next read |

So set any runtime override of `retry.*` before the `new`, not just before
`send()`.

## Adding your own keys

You may add your own keys to the published file. `mergeConfigFrom()` keeps the
published copy authoritative on the keys it shares with the packaged one and
leaves your extra keys alone.

The merge covers top-level keys only. A `rate_limit`, `retry` or `connections`
array in the published file wins over the packaged array as a whole, so a key you leave out
of it is absent rather than taken from the packaged file. Absent keys fall back
as listed under [Casts and fallbacks](#casts-and-fallbacks).

`sync` is merged one level deeper (the provider's `mergeableOptions()`), so a
published `sync` array that sets only `enabled` keeps the packaged values of
the others. An array inside it, `modules` or `exceptions`, still wins as a
whole.

## Casts and fallbacks

[`Cin7ServiceProvider`](../src/Cin7ServiceProvider.php) casts each value rather
than trusting its type, so a missing or loosely typed value does not throw a
`TypeError` when the container resolves the connector:

| Value | Cast | Result |
|---|---|---|
| `account_id`, `application_key` | `(string)` | Unset (`null`) becomes `''`. The connector resolves, and Cin7 rejects the call instead |
| `rate_limit.max`, `rate_limit.period` | `(int)`, falling back to `60` when the key is absent | A string such as `'30'` becomes `30`. A key present but `null` becomes `0`, which disables the window |
| `rate_limit.store` | `null` stays `null`; anything else `(string)` | `null` lets the store fallback chain apply |
| `connections.<name>.rate_limit.*` | the same casts | A key the connection leaves out is the top-level one, `store` included. A key present but `null` is not left out: `max` and `period` become `0`, and `store` stays `null` |

`Cin7Manager` applies these when it builds a connection. A name with no
`connections` entry, and not `default`, throws an `InvalidArgumentException`
naming it, as does an entry that is not an array.

[`Cin7Request`](../src/Requests/Cin7Request.php) applies the retry values with
its own fallbacks: a `null` or absent `retry.times` or `retry.delay_ms` falls
back to `4` and `5000`, and the results are clamped to at least one attempt and
at least `0` ms. See [requests](requests.md#retry-policy).

[`SyncConfig`](../src/Sync/SyncConfig.php) reads `sync.*` the same way: an absent or `null`
number falls back to its default, and each is clamped (`limit` to 1–1000, `timeout` to at
least 1, the others to at least 0). A blank `cron`, `full` or exception time schedules nothing,
an empty `queue` or `connection` is the default one, and an unknown module name, or a reference
book among the exceptions, throws an `InvalidArgumentException` rather than being skipped.
