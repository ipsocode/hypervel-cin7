# Contributing

This package is development-only until Hypervel 0.4 is released — see the
notice at the top of the [README](README.md). Changes land on `main` through
pull requests.

## Development setup

The suite needs PHP 8.4 or 8.5 with the Swoole and Redis extensions that
`hypervel/components` requires, plus PCOV for the coverage gate. The framework's
test case runs each test inside a Swoole coroutine, so a PHP without Swoole
fails before the first assertion. No Redis *server* is involved — nothing here
opens a Redis connection — but Composer will not resolve `hypervel/components`
without the extension. CI runs in `ghcr.io/ipsocode/hypervel/ci:<php>-latest`,
which has all of them, and is the simplest way to match it:

```sh
docker run --rm -it -v "$PWD":/app -w /app -e OTEL_SDK_DISABLED=true \
    ghcr.io/ipsocode/hypervel/ci:8.4-latest bash
composer update
```

`OTEL_SDK_DISABLED=true` matters in that image: it has no protobuf extension,
and without the variable the framework's OpenTelemetry exporters abort the run
before the first test.

No `composer.lock` is committed. Every install resolves against the current
`hypervel/components` `0.4.x-dev` on Packagist, as CI does, so an upstream
change that breaks this package shows up here first. Composer downloads
GitHub-hosted packages through GitHub's API; give it a GitHub token (for
example through `COMPOSER_AUTH`) if you hit the anonymous rate limit.

`config.platform` pins `ext-swoole` to `6.2.3`, the version in the CI image.
`hypervel/components` requires `^6.2.2`, so a lower pin fails resolution.

## Checks

| Command | What it runs |
|---|---|
| `composer conventions` | The conventions check (below), in plain PHP: no install needed |
| `composer lint` | php-cs-fixer in dry-run mode; `composer lint:fix` applies the fixes |
| `composer analyse` | PHPStan at level 5 over `src/` |
| `composer test` | The suite through the Testbench CLI |
| `composer test:phpunit` | The suite through PHPUnit directly, bypassing the Testbench CLI |
| `composer test:parallel` | The suite through ParaTest |
| `composer test:coverage` | The suite under PCOV, failing below 100% line coverage |
| `composer test:purge` | Clears the Testbench skeleton's cached config and runtime files |

Arguments after `--` reach PHPUnit or ParaTest, e.g.
`composer test -- --filter=RateLimitTest`.

The suite is split in two, and the split is enforced rather than conventional:

- **`tests/Unit`** — the endpoint table, the page defaults, the exception. Every
  method carries `#[UnitTest]`, so the framework is never booted for it and
  anything reaching for the container fails outright.
  The line is drawn by what the code actually touches, not by how simple it
  looks: the request classes read `config('cin7.retry.*')` in their
  constructor, so they are Feature tests.
- **`tests/Feature`** — everything needing the Testbench application: the
  container binding, the config merge, and every faked send.

Every send in the suite is faked through Saloon's mock client; nothing reaches
the live Cin7 API.

The environment the suite boots into lives in **two** files that must stay in
step: `testbench.yaml`, whose `env` block the Testbench CLI applies
(`composer test`), and `phpunit.xml`'s `<env>` block, which PHPUnit applies to
test methods — Testbench deliberately does not apply its own `env` there. Both
spell out the `CIN7_*` variables, so `config/cin7.php`'s `env()` calls are under
test rather than only its defaults. The credentials are fake on purpose: a run
that somehow reached `inventory.dearsystems.com` should be rejected rather than
authenticated.

`testbench.yaml` also names `Hypervel\Saloon\SaloonServiceProvider`
explicitly. A real application discovers it from the components manifest; the
Testbench skeleton does not.

### The Workbench application

`workbench/` is the throwaway host application the suite runs against. It owns
no models or migrations — this package touches no database — but it does own
the seam a consuming application has: `CustomerDirectory` takes the connector
by constructor injection, `Cin7Payloads` holds the Cin7 response envelopes the
suite asserts against, and `cin7:customers` is there to poke at the live API by
hand:

```sh
vendor/bin/testbench cin7:customers --limit=5
```

That one needs real credentials in `workbench/.env`, which is gitignored — keep
it that way. With the test credentials Cin7 answers `403 Incorrect
credentials!`, which is itself a useful signal that the auth headers are going
out.

### Coding standard and static analysis

php-cs-fixer and PHPStan are configured like `hypervel/components`:
`.php-cs-fixer.dist.php` loads its rules, verbatim, from
`.github/php-cs-fixer-rules.php`, and `phpstan.neon` carries its level. Keep
them in step when upstream changes, so a finding here is a finding there. Some
of the fixer's rules are risky — they change behaviour, not only formatting —
so run `composer test` after `composer lint:fix`, not just before committing.
An inline PHPStan ignore names its error and says why:
`@phpstan-ignore <identifier> (<reason>)`.

### The conventions check

The conventions check, `.github/scripts/conventions.php`, fails CI on what a
reviewer used to check by eye: `Illuminate\` or `Laravel\` anywhere (docblocks
and strings too), and here `GuzzleHttp\` and `curl_*` as well, since the
transport is `hypervel/saloon`; container array-access, `@codeCoverageIgnore`,
a bare `@phpstan-ignore-line`, a coverage gate below 100%, and raw SQL,
`eval()`, `unserialize()`, shell commands, `sleep()` or `exit` in shipped code.
It also holds the names this package writes where the application writes too:
context keys `__cin7.*`, cache and lock keys `cin7:*`, commands `cin7:<verb>`,
publish tags `cin7-*`, env vars `CIN7_*` and `config/cin7.php`. Each violation
is reported on its line. This package's settings are `.github/conventions.php`;
a genuine exception is an entry in its `allowed` list,
`'<path>' => [<exact number of hits>, '<why>']`, and the check fails once the
count stops matching either way.

### CI and the automated review

The automated review also runs `.github/scripts/review-scan.sh`, which flags what
a diff adds or removes. To see what it will flag on your branch:
`git diff origin/main...HEAD | bash .github/scripts/review-scan.sh`. The
review's instructions are the `pr-review` skill,
`.github/claude/skills/pr-review/SKILL.md`: ask Claude Code to follow it on your
branch to get the same review before you push.

CI (`.github/workflows/tests.yml`) runs for every pull request, each job once
the one it needs has passed: the conventions check, on the bare runner in
seconds (`.github/workflows/initial.yml`); code style, PHPStan and the suite
under the 100% coverage gate on PHP 8.4; then, side by side, PHPStan and the
suite on PHP 8.5, and the automated review. A push to a branch without a pull
request runs nothing, so open a draft pull request to get CI early; the
automated review runs once the pull request is marked ready, on each commit that
passes the conventions check and PHP 8.4. It keeps notes between pushes, so a
push is reviewed for what it changed, and for whatever else in the pull request
that reaches. `main` changes only through a pull request that is up to date with
it, passes those checks and has every review conversation resolved; a change
that leaves a line of `src/` uncovered fails its own pull request. The review
never blocks on what it finds; it comments, and each comment is a conversation
to resolve.

Most of `.github/` is shared by the ipsocode/hypervel-* packages and imported
from one copy: the workflows, the scripts, the php-cs-fixer rules, the issue and
pull request templates, `CODEOWNERS`, `dependabot.yml`, the release-notes
config, the security policy, and the automated review's skill and brief in
`.github/claude/`, which the review installs on its runner for the review only.
Each of them but the pull request template says so in its first lines. A pull
request may still change one; the maintainer carries the change into the shared
copy, and the next import brings it to every package. Two parts of `.github/`
are this package's own: `conventions.php`, the check's settings, and `review/`,
which tells the automated review what this package is and where to look. The
package keeps no `.claude/`: `.gitignore` leaves Claude Code's local state out.

## Coroutine safety

A Hypervel worker is long-lived and serves many requests at once as coroutines,
so state that would be per-request in PHP-FPM is shared here. The connector is
a container singleton: one instance serves every coroutine in a worker for the
worker's lifetime. Every change is held to these rules:

- No `static` property. If one ever becomes unavoidable, it needs a
  `flushState()`, reached from a `src/Testing/TestState.php` registrar declared
  in `composer.json`'s `extra.hypervel.test-state`, so a consuming
  application's suite resets it between tests.
- The connector and the requests hold only readonly values; nothing is mutated
  per send.
- No native `sleep()`/`usleep()`. Waits go through the framework's `Sleep` and
  rate limiter, which the connector already uses and which suspend only the
  calling coroutine.
- Transport stays on `hypervel/saloon`, with no hand-rolled Guzzle or cURL
  client. Rate-limit and cooldown keys stay scoped to the Cin7 account.
- Hypervel APIs only, never `Illuminate\*`. Tests reach the container with
  `$app->get(...)`, never array access.
- New behaviour comes with a test. `@codeCoverageIgnore` is not a way past the
  coverage gate.

`phpunit.xml` registers the framework's `AfterEachTestExtension` even though
the package declares no test-state registrar, so the day that stops being true
it fails loudly rather than silently.

## Pull requests

Fill in the pull request template and label the pull request with the type it
ticks. There is no changelog file: each release's notes are generated from the
titles of the pull requests it contains, grouped by those labels
(`.github/release.yml`), so write the title for someone reading the release. A
pull request labelled `breaking-change` makes the next release at least a minor
version.

A change a consumer will observe, such as a rate-limit key, the retry and backoff
policy or the shape of a request, goes in the template's *Behaviour change*
section, with the README update it needs in the same pull request.

Never put a real Cin7 account ID or application key in a commit, a fixture or a
pull request description. Security problems are reported privately, as
[SECURITY.md](.github/SECURITY.md) describes.

## Releasing

For maintainers. A release is an annotated `vX.Y.Z` tag on `main` plus a GitHub
Release carrying the notes. Composer reads versions off the tags; nothing is
published to Packagist.

Run the **publish** workflow on `main` (Actions → publish → *Run workflow*).
Leave the bump on `auto` — minor if a pull request merged since the previous
tag is labelled `breaking-change`, patch otherwise — or pick `patch`, `minor`
or `major`, or type an exact version. *Highlights* go at the top of the notes;
*dry run* shows the plan without tagging.

The workflow runs the full suite on PHP 8.4 and 8.5, with the coverage gate on
8.4, and only then tags the commit and publishes the Release. The notes are
generated from the pull requests merged since the previous tag. A `v*` tag
pushed by hand goes through the same suite and gets the same Release.

Composer installs a release as GitHub's archive of its tag, which leaves out
every path `.gitattributes` marks `export-ignore`: `.github/`, `docs/`, the
tests, the workbench and the development configs. A new top-level file or
directory ships unless it is added there; `git archive HEAD | tar -t` lists
what would.
