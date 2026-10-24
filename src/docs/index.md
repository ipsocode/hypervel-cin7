# hypervel-cin7 documentation

These pages document the package for an application that uses it, in reading order: start with
getting started and configuration, then the pages on calling Cin7 as you need them.

## Start here

- [Getting started](getting-started.md) — installing the package while Hypervel 0.4 is a dev
  branch, what registers itself, the credentials, checking them with `cin7:check`, the first
  calls, and what the package deliberately does not do.
- [Configuration](configuration.md) — every configuration key with its environment variable
  and default, publishing the config, and when the values are read.

## Calling Cin7

- [Resources](resources.md) — the accessor tree, the conventions every resource and request
  follows, and adding a new resource method.
- [Requests](requests.md) — the three request bases and the request lines they send, the wire
  protocol, the exceptions a failed call throws, and the bounded throttling retry.
- [Data objects](data.md) — the typed request and response bodies: the conventions every data
  class follows, the class behind each path, and the empty-collection rule.
- [Pagination](pagination.md) — walking every page of a listing or sending the pages
  concurrently, and how the last page is worked out from Cin7's list envelope.

## Running it

- [The connector](connector.md) — the shared connector, its transport and timeouts, rate
  limiting and the choice of limiter store, the throttling cooldown, and checking a connection
  with `cin7:check`.
- [Sync](sync.md) — the optional scheduled copy of Cin7's records in one local table: the
  modules and their order, how a pull works, the schedule, the `cin7:sync` command and reading
  the table.

## Testing

- [Testing](testing.md) — faking Cin7 in the tests of an application that uses the package,
  with the shipped `Cin7Fake` builders for list envelopes and the Error Model, and how the
  package's own suite is built.

Working on the package itself? The development setup and the checks CI runs are in
[CONTRIBUTING.md](../../CONTRIBUTING.md).
