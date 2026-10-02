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
| `rate_limit.max` | `CIN7_RATE_MAX` | `60` | Calls allowed per window, per API application; `0` disables the window (the throttling cooldown still applies) |
| `rate_limit.period` | `CIN7_RATE_PERIOD` | `60` | Window length in seconds; `0` also disables the window |
| `rate_limit.store` | `CIN7_RATE_STORE` | none | Rate limiter store; unset falls back to `saloon.rate_limiter.store`, then `rate-limiter.default` (see [connector](connector.md)) |
| `retry.times` | `CIN7_RETRY_TIMES` | `4` | Total attempts on a 429 or 503, not extra ones |
| `retry.delay_ms` | `CIN7_RETRY_DELAY_MS` | `5000` | Milliseconds between attempts |

The credentials go in your environment:

```dotenv
CIN7_ACCOUNT_ID=
CIN7_APPLICATION_KEY=
```

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
| `account_id`, `application_key`, `rate_limit.*` | The first time the container resolves `Cin7Connector` | None for that worker: the connector is a singleton built once per worker |
| `retry.*` | Each time a request is constructed (`new GetCustomer(...)` and the rest) | Applies to requests constructed after the change |

So set any runtime override of `retry.*` before the `new`, not just before
`send()`.

## Adding your own keys

You may add your own keys to the published file. `mergeConfigFrom()` keeps the
published copy authoritative on the keys it shares with the packaged one and
leaves your extra keys alone.

The merge covers top-level keys only. A `rate_limit` or `retry` array in the
published file wins over the packaged array as a whole, so a key you leave out
of it is absent rather than taken from the packaged file. Absent keys fall back
as listed under [Casts and fallbacks](#casts-and-fallbacks).

## Casts and fallbacks

[`Cin7ServiceProvider`](../src/Cin7ServiceProvider.php) casts each value rather
than trusting its type, so a missing or loosely typed value does not throw a
`TypeError` when the container resolves the connector:

| Value | Cast | Result |
|---|---|---|
| `account_id`, `application_key` | `(string)` | Unset (`null`) becomes `''`. The connector resolves, and Cin7 rejects the call instead |
| `rate_limit.max`, `rate_limit.period` | `(int)`, falling back to `60` when the key is absent | A string such as `'30'` becomes `30`. A key present but `null` becomes `0`, which disables the window |
| `rate_limit.store` | `null` stays `null`; anything else `(string)` | `null` lets the store fallback chain apply |

[`Cin7Request`](../src/Requests/Cin7Request.php) applies the retry values with
its own fallbacks: a `null` or absent `retry.times` or `retry.delay_ms` falls
back to `4` and `5000`, and the results are clamped to at least one attempt and
at least `0` ms. See [requests](requests.md#retry-policy).
