<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Testbench\TestCase as BaseTestCase;

/**
 * Base for tests of the package's pure logic — the endpoint table, the page
 * defaults, the exception type. None of it touches the container, a config
 * repository or the network.
 *
 * Every test method here carries `#[UnitTest]`, which tells the framework to
 * skip building and booting a Testbench application for that method. That is
 * not just a speed trick: it is what proves these units really are free of the
 * framework, because anything that reaches for a facade or the container fails
 * outright instead of quietly working off an application the test never needed.
 *
 * The line this draws is real rather than arbitrary. `Endpoint` and
 * `PageDefaults` are pure, so they live here; the request classes look pure but
 * read `config('cin7.retry.*')` in their constructor, so they are Feature tests
 * however simple their assertions look.
 */
abstract class TestCase extends BaseTestCase
{
}
