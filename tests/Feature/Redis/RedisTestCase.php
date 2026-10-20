<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\Contracts\Foundation\Application as ApplicationContract;
use Hypervel\Foundation\Testing\Concerns\InteractsWithRedis;
use Hypervel\Foundation\Testing\RedisTestConfiguration;
use Hypervel\Testbench\Attributes\WithConfig;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * A test that needs a real Redis, which only the Redis CI jobs provide: it skips unless `REDIS_HOST`
 * or `REDIS_CLUSTER_HOSTS_AND_PORTS` is set. The module locks and the rate limiter both use it, with
 * every key under one prefix, so the worker's database is flushed around each test.
 *
 * @see docs/testing.md
 */
#[WithConfig('cin7.sync.enabled', true)]
abstract class RedisTestCase extends TestCase
{
    use InteractsWithRedis;

    /**
     * Skipped before the application boots: with the stores pointed at Redis, booting reaches for a
     * server that a run without one does not have.
     */
    protected function setUp(): void
    {
        if (! $this->hasExplicitRedisConfig()) {
            $this->markTestSkipped('Set REDIS_HOST or REDIS_CLUSTER_HOSTS_AND_PORTS to run the Redis tests for ' . static::class);
        }

        parent::setUp();
    }

    protected function tearDown(): void
    {
        // A skipped test has no application to tear down.
        if (! isset($this->app)) {
            return;
        }

        parent::tearDown();
    }

    protected function defineEnvironment(ApplicationContract $app): void
    {
        $config = $app->make('config');

        $config->set('cache.default', 'redis');
        $config->set('cin7.rate_limit.store', 'redis');
        $config->set('rate-limiter.default', 'redis');
        $config->set('database.redis.default.options.prefix', 'cin7-test:');

        // Before the application boots, so no pool is opened on the base database the workers share.
        if ($this->hasExplicitRedisConfig()) {
            RedisTestConfiguration::configure($config, $this->parallelTestingToken());
        }
    }
}
