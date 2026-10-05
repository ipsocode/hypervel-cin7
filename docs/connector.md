# The connector

[`Cin7Connector`](../src/Cin7Connector.php) is the one object every Cin7 call
goes through. It names the base URL and the auth headers, sends over the
framework's shared HTTP connection, throttles every call against one window per
Cin7 API application, puts the application into a short cooldown when Cin7
answers 429 or 503, and fails any response whose body is Cin7's Error Model.
This page covers how it is shared, how it reaches Cin7, and how its rate
limiting behaves; the requests it sends are in [requests](requests.md) and its
configuration keys in [configuration](configuration.md).

## One connector per connection per worker

`Cin7ServiceProvider` resolves the default connector through `Cin7Manager`, which
builds each connection once (see [named connections](#named-connections)). A
connector holds only readonly values, all set in its constructor: the account ID,
the application key and the three rate limit settings, and the headers, rate
limit key and rate limit policy it builds from them once rather than on every
send. Nothing is mutated per send, so one instance serves every coroutine in a
worker for the worker's lifetime. Inject it rather than building one per call:

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}
```

The credentials are constructor-injected. The provider reads them from
`config('cin7.*')` when a connection is first resolved and passes them in;
nothing calls `env()` or `getenv()` at request time. A second Cin7 account,
such as a sandbox next to production, is a named connection (below), and it
throttles on its own key, as does a second API application of the same account.

`accountId()` returns the account the connector calls, which the [sync](sync.md) keys its rows
by. It is a credential: log or print neither it nor the application key.

| Constructor argument | Default | Config key |
|---|---|---|
| `accountId` | none | `cin7.account_id` |
| `applicationKey` | none | `cin7.application_key` |
| `rateLimitMax` | `60` | `cin7.rate_limit.max` |
| `rateLimitPeriod` | `60` | `cin7.rate_limit.period` |
| `rateLimitStore` | `null` | `cin7.rate_limit.store` |
| `rateLimitCooldown` | `5` | `cin7.rate_limit.cooldown` |

The keys are the `default` connection's; a named connection reads the same six
from its own entry.

## Named connections

`Cin7Manager`, a container singleton, resolves a connector by connection name.
Each is built the first time it is asked for, with the casts the
[configuration](configuration.md#casts-and-fallbacks) lists, and kept in a map
for the worker's life. The connectors are immutable, so sharing the map across
coroutines is safe.

```php
// config/cin7.php
'connections' => [
    'sandbox' => [
        'account_id' => env('CIN7_SANDBOX_ACCOUNT_ID'),
        'application_key' => env('CIN7_SANDBOX_APPLICATION_KEY'),
        'rate_limit' => ['max' => 30],
    ],
],
```

```php
use Ipsocode\Cin7\Cin7Manager;

public function __construct(private readonly Cin7Manager $cin7) {}

$production = $this->cin7->connection();          // the default connection
$sandbox = $this->cin7->connection('sandbox');
```

- **The default.** The top-level `account_id`, `application_key` and
  `rate_limit` are the implicit `default` connection, so a published config and
  the current env vars keep working unchanged. `cin7.default`
  (`CIN7_CONNECTION`) names the connection that `connection()` without a name
  and an injected `Cin7Connector` resolve.
- **Rate limit.** A connection with no `rate_limit`, or with some of its keys,
  takes the top-level values for the rest.
- **Unknown names.** A name that is neither in `connections` nor `default`
  throws an `InvalidArgumentException` naming it.
- **Isolation.** The window and the cooldown share one key,
  `cin7:api:<accountId>:<digest>`, so two connections with different accounts
  or application keys never throttle or cool down each other, even on a shared
  store. Two connections with the same account and key share one window, as
  they should: Cin7 meters the API application.

## Resources

The connector exposes one accessor per Cin7 resource, each returning a fresh
instance built on the connector:

```php
$this->cin7->customer(); // CustomerResource
```

A resource holds no state of its own beyond the connector, so a fresh
instance per call never threatens the connector's coroutine safety. The
accessor tree, the conventions every resource follows and the requests a
resource builds are in [resources](resources.md).

## Transport

The base URL is `https://inventory.dearsystems.com/ExternalApi/v2/`, and every
request carries `Content-Type: application/json`, `api-auth-accountid` and
`api-auth-applicationkey`; the full wire format is in
[requests](requests.md#wire-protocol).

The connector does not choose an HTTP connection of its own, so it sends over
the framework's `saloon` connection, which `hypervel/saloon` registers from its
`saloon.connection` config. Its shipped options are:

| Option | Value |
|---|---|
| `connect_timeout` | 10 s |
| `timeout` | 30 s |
| `transport_sharing` | cURL handlers shared across the worker |

A call that hangs therefore fails after 30 seconds with a
`FatalRequestException`, which is not retried. Those options belong to the
application's Saloon configuration and apply to every Saloon connector in it.

## Rate limiting

The connector uses Saloon's `HasRateLimits` with a single policy: `max` calls
per `period` seconds (60 per 60 by default), keyed
`cin7:api:<accountId>:<digest>`, where the digest is the first 16 hex digits of
the SHA-256 of the application key. The key never carries the application key
itself, and the limiter store only ever sees a hash of the whole key.

- **Per application, not per endpoint.** Every endpoint and every verb draws on
  the same window. Cin7's reference applies its limit "on per API Application
  basis", and one account can have several applications.
- **Per account and application.** Two connectors for different Cin7 accounts
  (tenants, or a sandbox alongside production), or for two applications of one
  account, never throttle each other, even on a shared store.
- **Off switch.** A `max` or `period` of zero or less removes the window. The
  connector still reads the 503 cooldown from the store on every send, and
  still records one after a 503.
- **Waits rather than throws.** When the window is exhausted, the call waits
  for capacity instead of failing. The wait goes through
  `Hypervel\Support\Sleep`, whose native sleep is Swoole-hooked, so it suspends
  only the calling coroutine; the worker keeps serving the others.

The limit applies to calls that go out on the wire. A faked send is never
throttled; see [testing](testing.md).

## Choosing the limiter store

The window lives in a framework rate limiter store, chosen in this order:

1. `cin7.rate_limit.store` (`CIN7_RATE_STORE`)
2. `saloon.rate_limiter.store`
3. `rate-limiter.default`, which an application ships as `database` unless
   `RATE_LIMITER_STORE` says otherwise

The limit only holds across the application if every worker and server that
calls Cin7 with it shares the store:

| Store | Shared by |
|---|---|
| `database` (the framework default) | every worker and server using that database |
| `redis` | every worker and server using that Redis connection |
| `swoole` | the workers of one server only; the effective limit multiplies by the server count |
| `worker-array` (the Testbench default) | one worker only; the effective limit multiplies by the worker count. Meant for tests |

With the `database` store:

- The application needs the framework's `rate_limits` table
  (`php artisan make:rate-limiter-table` creates its migration).
- A Cin7 call made while the limiter's database connection is inside a
  transaction throws a `LogicException`. If the application calls Cin7 from
  inside transactions, give the store a connection of its own
  (`rate-limiter.stores.database.connection`) or use `redis`.

If the limiter store is unreachable (the database, or Redis if chosen), the
limiter's exception is thrown from `send()`, so the call fails rather than
going out unthrottled. The connector cannot catch this: `SaloonManager` touches
the store when it consumes, not in `resolveRateLimits()`. It happens even with
`rate_limit.max` at `0`, because the 503 cooldown is still read from the store;
point `rate_limit.store` at a store that is reachable.

## The throttling cooldown

Cin7's reference documents `429 Too Many Requests` for its limit of 60 calls a
minute, and Cin7 also answers 503 when it throttles. The connector maps both to
a cooldown:

| Response | Cooldown |
|---|---|
| 429 with `Retry-After` | the seconds `Retry-After` names (seconds or an HTTP date) |
| 429 without `Retry-After` | `rate_limit.cooldown`, 5 seconds by default |
| 503 | `rate_limit.cooldown`; Cin7 sends no `Retry-After` with it |
| anything else | none |

`rate_limit.cooldown` (`CIN7_RATE_COOLDOWN`) reaches the connector as its sixth
constructor argument, `$rateLimitCooldown`, which defaults to
`Cin7Connector::THROTTLE_COOLDOWN` (5). A 429's own `Retry-After` always wins.
At `0` or less, a 503 and a 429 without `Retry-After` record no cooldown at all,
and `resolveRateLimitCooldown()` returns `null` for them.

The cooldown is recorded in the same limiter store under the same logical key as
the window (the store keeps the two apart), and every send for that application
checks it before taking from the window, so the coroutines sending for a
throttled application wait the throttle out instead of adding to it. A
throttling response on one application never cools down another.

The cooldown sits alongside the request's own retry on 429 and 503 (see
[retry policy](requests.md#retry-policy)): a retried attempt passes the same
cooldown and limit check as a fresh send, so a 429's `Retry-After` is honoured
even when it is longer than the retry delay.

Saloon calls `resolveRateLimitCooldown()` for every response that came off the
wire, 200s included, and never for a mocked or cached one. That is why it must
return `null` for anything but a 429 or 503, and why the suite tests it by
calling it directly.

## Error Model responses

Cin7 reports a failure with its Error Model, `{"ErrorCode": …, "Exception": "…"}`,
sometimes inside a list, and sometimes with a 200. The connector's
`hasRequestFailed()` treats any body carrying `ErrorCode` (at the top level, or
in the first item of a list) as failed, so the request throws whatever the
status; see [errors](requests.md#errors).

## Editing the rate-limit hooks

The connector is `final`, so this concerns changes to the package itself.

### `resolveRateLimitCooldown()`

This is a method of the `HasRateLimits` trait, not of `Connector`. The
connector's own method shadows the trait's, so
`parent::resolveRateLimitCooldown()` is a fatal error. The connector reuses the
trait's `Retry-After` parser for a 429 through an alias:

```php
use HasRateLimits {
    resolveRateLimitCooldown as retryAfterCooldown;
}
```

### `resolveRateLimitCooldownKey()`

The trait's default keys the cooldown on `static::class` alone, so without this
override a throttling response on one Cin7 application would put every
application sharing the store into cooldown. Keep the account and the
application key's digest in the key.

### `resolveRateLimitStore()`

It keeps the trait's `UnitEnum|string|null` return type on purpose, so it has
the same signature as the `HasRateLimits` method it overrides. The connector
only ever returns a string or `null`, so PHPStan reports the `UnitEnum` arm as
unused; [`phpstan.neon`](../phpstan.neon) ignores `return.unusedType` for
`src/Cin7Connector.php` for that reason.
