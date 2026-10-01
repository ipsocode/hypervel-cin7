<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Testbench\TestCase as BaseTestCase;

/**
 * Base for tests of pure logic; every method carries `#[UnitTest]`, so no application boots.
 *
 * @see docs/testing.md
 */
abstract class TestCase extends BaseTestCase
{
}
