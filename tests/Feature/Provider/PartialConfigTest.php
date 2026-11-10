<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Provider;

use Hypervel\Testbench\Attributes\WithConfig;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * A published config that sets one key of `rate_limit` or `retry` keeps the package's others.
 */
#[WithConfig('cin7.rate_limit.max', 30)]
#[WithConfig('cin7.retry.times', 2)]
class PartialConfigTest extends TestCase
{
    public function testAPartialRateLimitBlockKeepsThePackagedKeys(): void
    {
        $this->assertSame(30, config('cin7.rate_limit.max'));
        $this->assertSame(60, config('cin7.rate_limit.period'));
        $this->assertSame(Cin7Connector::THROTTLE_COOLDOWN, config('cin7.rate_limit.cooldown'));
    }

    public function testAPartialRetryBlockKeepsThePackagedKeys(): void
    {
        $this->assertSame(2, config('cin7.retry.times'));
        $this->assertSame(5000, config('cin7.retry.delay_ms'));
        $this->assertSame(1.0, config('cin7.retry.backoff'));
        $this->assertSame(0, config('cin7.retry.max_delay_ms'));
        $this->assertSame(0, config('cin7.retry.jitter_ms'));
    }
}
