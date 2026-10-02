# The connector

[`Cin7Connector`](../src/Cin7Connector.php) is the one object every Cin7 call
goes through. It names the base URL and the auth headers, sends over the
framework's shared HTTP connection, throttles every call against one window per
Cin7 account, and puts the account into a short cooldown when Cin7 answers 503.
This page covers how it is shared, how it reaches Cin7, and how its rate
limiting behaves; the requests it sends are in [requests](requests.md) and its
configuration keys in [configuration](configuration.md).

## One connector per worker

`Cin7ServiceProvider` binds the connector as a container singleton. It holds
only readonly scalars (the account ID, the application key and the three rate
limit settings) and nothing is mutated per send, so one instance serves every
coroutine in a worker for the worker's lifetime. Inject it rather than building
one per call:

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}
```

The credentials are constructor-injected. The provider reads them from
`config('cin7.*')` when the singleton is first resolved and passes them in;
nothing calls `env()` or `getenv()` at request time. A second Cin7 account,
such as a sandbox next to production, is a second instance built with its own
credentials, and it throttles on its own key:

```php
$sandbox = new Cin7Connector($sandboxAccountId, $sandboxApplicationKey);
```

| Constructor argument | Default | Config key |
|---|---|---|
| `accountId` | none | `cin7.account_id` |
| `applicationKey` | none | `cin7.application_key` |
| `rateLimitMax` | `60` | `cin7.rate_limit.max` |
| `rateLimitPeriod` | `60` | `cin7.rate_limit.period` |
| `rateLimitStore` | `null` | `cin7.rate_limit.store` |

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
per `period` seconds (60 per 60 by default), keyed `cin7:api:<accountId>`.

- **Account-wide, not per endpoint.** Every endpoint and every verb draws on
  the same window, because Cin7 meters the account, not the endpoint.
- **Per account.** Two connectors for different Cin7 accounts (tenants, or a
  sandbox alongside production) never throttle each other, even on a shared
  store.
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

The limit is only account-wide if every worker and server that calls Cin7
shares the store:

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

## The 503 cooldown

Cin7 signals throttling with a 503 and no `Retry-After` header. The connector
maps that to a 5-second cooldown: `resolveRateLimitCooldown()` returns `5` for
a 503 and `null` for every other status. The cooldown is recorded in the same
limiter store under `Ipsocode\Cin7\Cin7Connector:<accountId>`, and every send
for that account checks it before taking from the window, so the coroutines
sending for a throttled account wait the throttle out instead of adding to it.
The key includes the account for the same reason the limit's does: a 503 on one
account never cools down another.

The cooldown sits alongside the request's own 503 retry (see
[retry policy](requests.md#retry-policy)): a retried attempt passes the same
cooldown and limit check as a fresh send.

Saloon calls `resolveRateLimitCooldown()` for every response that came off the
wire, 200s included, and never for a mocked or cached one. That is why it must
return `null` for anything but a 503, and why the suite tests it by calling it
directly.

## Editing the rate-limit hooks

The connector is `final`, so this concerns changes to the package itself.

### `resolveRateLimitCooldown()`

This is a method of the `HasRateLimits` trait, not of `Connector`. The
connector's own method shadows the trait's, so
`parent::resolveRateLimitCooldown()` is a fatal error. To reuse the trait's
parser (429 with `Retry-After`) alongside Cin7's 503, alias it:

```php
use HasRateLimits {
    resolveRateLimitCooldown as baseResolveRateLimitCooldown;
}
```

### `resolveRateLimitCooldownKey()`

The trait's default keys the cooldown on `static::class` alone, so without this
override a 503 on one Cin7 account would put every account sharing the store
into cooldown. Keep the account ID in the key.

### `resolveRateLimitStore()`

It keeps the trait's `UnitEnum|string|null` return type on purpose, so it has
the same signature as the `HasRateLimits` method it overrides. The connector
only ever returns a string or `null`, so PHPStan reports the `UnitEnum` arm as
unused; [`phpstan.neon`](../phpstan.neon) ignores `return.unusedType` for
`src/Cin7Connector.php` for that reason.
