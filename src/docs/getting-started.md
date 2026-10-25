# Getting started

This page takes an application from `composer require` to its first Cin7 call: installing the
package while Hypervel 0.4 is a dev branch, what registers itself, the credentials and how to
check them, and the calls most code starts with. It ends with what the package leaves to the
application on purpose. Every configuration key is in [configuration](configuration.md).

## Installing

```sh
composer require ipsocode/hypervel-cin7
```

Hypervel 0.4 is only available as a dev branch, so your application's `composer.json` must
already allow it: `"minimum-stability": "dev"` together with `"prefer-stable": true`.

Tags are not re-tested as `0.4.x-dev` moves on. `main` is: a scheduled workflow re-tests it
each night that `0.4.x-dev` has moved, and while `main` fails against the current
`0.4.x-dev`, an issue titled "Nightly components check failed" is open in the package's
repository. To pick up changes as they land, require `ipsocode/hypervel-cin7:dev-main`
instead. Each release's notes, breaking changes first, are on the
[Releases](https://github.com/ipsocode/hypervel-cin7/releases) page.

## What registers itself

The service provider (`Ipsocode\Cin7\Cin7ServiceProvider`) is discovered through the
package's `extra.hypervel` block, and `hypervel/saloon`'s own provider through the components
manifest, so there is nothing to register and no Saloon wiring to do.

Publishing the config is optional, because the packaged defaults are merged in either way:

```sh
php artisan vendor:publish --tag=cin7-config
```

[Publishing](configuration.md#publishing) covers what that copies and when the provider
offers the group.

## Credentials

The package needs a Cin7 Core account with API access: its account ID and an application key.
The credentials go in your environment:

```dotenv
CIN7_ACCOUNT_ID=
CIN7_APPLICATION_KEY=
```

Every other key, with its environment variable and default, is in
[configuration](configuration.md#keys).

## Checking the setup

`php artisan cin7:check` sends one `GET me` with a connection's credentials (`--connection=`
names one) and prints the company Cin7 answers with, or why the call failed, without printing
either credential. Run it before the first real call, so a wrong key or an unreachable limiter
store shows up there; its options, steps and failures are in
[checking a connection](connector.md#checking-a-connection).

## First calls

Inject `Cin7Connector` and call a [resource](resources.md) accessor; the accessor chain spells
the V2 path.

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->customer()->get()->json();

// POST customer, raw JSON body, no page/limit
$new = $this->cin7->customer()->post(['Name' => 'ACME'])->json();

// PUT customer, body carries ID
$this->cin7->customer()->put(['ID' => $guid, 'Name' => 'ACME Ltd']);

// Every customer, across all pages
foreach ($this->cin7->customer()->paginate()->items() as $customer) {
    // …
}
```

Each connector holds only readonly scalars and is never mutated per request, so sharing one
instance across coroutines for a worker's lifetime is safe; see
[the connector](connector.md#one-connector-per-connection-per-worker). Injecting
`Cin7Connector` gives the default connection.

For a second account, such as a sandbox, add it under `connections` in `config/cin7.php` and
ask `Cin7Manager` for it by name:

```php
use Ipsocode\Cin7\Cin7Manager;

$sandbox = app(Cin7Manager::class)->connection('sandbox');
```

Each connection throttles on its own, as [named connections](connector.md#named-connections)
describes. Walking or fetching every page is in [pagination](pagination.md), and typed request
and response bodies in [data](data.md).

## What the package does not do

These are left to the application on purpose, because they are its policy rather than the
client's:

- **No caching.** Response caching, cache-key shape and cache-hit logging semantics are
  consumer policy; `Cacheable`/`HasCaching` can be adopted later once a second consumer's
  needs are known. The [sync](sync.md), a local copy of Cin7's records, is a separate, opt-in
  table: off by default, it creates no table and runs no query.
- **No mandatory DTOs.** Arrays work everywhere: a write takes an array body and `json()`
  returns the decoded array. Typed data objects are an additive layer on top
  ([data](data.md)), shown here for a customer:

  ```php
  use Ipsocode\Cin7\Data\Customer\CustomerPostData;

  // The fields the reference requires must be given; of the rest, only the keys you set are sent.
  $response = $cin7->customer()->post(CustomerPostData::from([
      'Name' => 'ACME',
      'Status' => 'Active',
      'Currency' => 'GBP',
      'PaymentTerm' => '30 days',
      'AccountReceivable' => '610',
      'RevenueAccount' => '200',
      'TaxRule' => 'Tax Exempt',
  ]));

  $customer = $response->dto();                      // CustomerData
  $customers = $cin7->customer()->get()->dto();      // list<CustomerData>
  ```

- **No request logging.** Instrumentation writes consumer-owned models, so recording the
  calls the package sends is the application's to do.
