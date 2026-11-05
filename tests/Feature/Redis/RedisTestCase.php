<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\Contracts\Foundation\Application as ApplicationContract;
use Hypervel\Redis\RedisConnection;
use Hypervel\Redis\RedisProxy;
use Hypervel\Support\Facades\Redis;
use Hypervel\Testbench\Attributes\WithConfig;
use Ipsocode\Cin7\Tests\TestCase;
use Mockery as m;
use Mockery\MockInterface;

/**
 * A test of what the module locks and the rate limiter send to Redis. The cache and the limiter
 * both resolve their connection through the `Redis` facade, so each test mocks it and asserts the
 * commands and keys that reach the connection, with no server to start or skip for.
 *
 * @see src/docs/testing.md
 */
#[WithConfig('cin7.sync.enabled', true)]
abstract class RedisTestCase extends TestCase
{
    /**
     * The proxy the mocked `Redis` facade hands out for every connection name.
     *
     * @var MockInterface&RedisProxy
     */
    protected RedisProxy $redisProxy;

    /**
     * The pooled connection the proxy lends to a `withConnection()` callback.
     *
     * @var MockInterface&RedisConnection
     */
    protected RedisConnection $redisConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redisProxy = m::mock(RedisProxy::class);
        $this->redisConnection = m::mock(RedisConnection::class);

        Redis::shouldReceive('connection')->andReturn($this->redisProxy);
    }

    protected function defineEnvironment(ApplicationContract $app): void
    {
        $config = $app->make('config');

        $config->set('cache.default', 'redis');
        $config->set('cin7.rate_limit.store', 'redis');
        $config->set('rate-limiter.default', 'redis');
    }

    /**
     * Run the callback a `withConnection()` call is given against the mocked pooled connection.
     */
    protected function lendConnection(): void
    {
        $this->redisProxy->shouldReceive('withConnection')
            ->andReturnUsing(fn (callable $callback): mixed => $callback($this->redisConnection));
    }
}
