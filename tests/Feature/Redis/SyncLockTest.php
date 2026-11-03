<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\Foundation\Testing\RefreshDatabase;
use Hypervel\Redis\LuaScripts;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Facades\Cache;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Synchroniser;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Mockery as m;

/**
 * The sync's module lock lives in the shared cache store, so a pull on one worker holds off a pull
 * of the same module on another. The `Redis` facade is mocked, so each test asserts the key and the
 * command the lock sends: that is all a second worker, which shares only the server, depends on.
 */
class SyncLockTest extends RedisTestCase
{
    use RefreshDatabase;

    private const LOCK = 'cin7:sync:acct-test:customer';

    public function testALockTakenByAnotherWorkerMakesThePullSkipTheModule(): void
    {
        $mock = Saloon::fake([]);

        $this->redisProxy->expects('set')
            ->with($this->prefixed(self::LOCK), m::type('string'), 'EX', m::type('int'), 'NX')
            ->andReturnFalse();

        $result = $this->app->make(Synchroniser::class)->pull(Module::Customer);

        $this->assertTrue($result->skipped);
        $this->assertFalse($result->failed());
        $mock->assertNothingSent();
    }

    public function testThePullReleasesItsLockForAnotherWorker(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList')]);

        $owner = null;

        $this->redisProxy->expects('set')
            ->with($this->prefixed(self::LOCK), m::type('string'), 'EX', m::type('int'), 'NX')
            ->andReturnUsing(function (string $key, string $value) use (&$owner): bool {
                $owner = $value;

                return true;
            });

        $this->lendConnection();
        $this->redisConnection->expects('pack')->andReturnUsing(fn (array $values): array => $values);
        $this->redisConnection->expects('eval')
            ->with(LuaScripts::releaseLock(), 1, $this->prefixed(self::LOCK), m::on(static function (string $value) use (&$owner): bool {
                return $value === $owner;
            }))
            ->andReturn(1);

        $this->app->make(Synchroniser::class)->pull(Module::Customer);
    }

    public function testTheLockOfOneModuleDoesNotHoldBackAnother(): void
    {
        Saloon::fake([Cin7Fake::list('SupplierList')]);

        // Only the customer lock is taken: the supplier's own key is free.
        $this->redisProxy->expects('set')
            ->with($this->prefixed('cin7:sync:acct-test:supplier'), m::type('string'), 'EX', m::type('int'), 'NX')
            ->andReturnTrue();
        $this->redisProxy->shouldReceive('set')
            ->with($this->prefixed(self::LOCK), m::any(), m::any(), m::any(), m::any())
            ->never();

        $this->lendConnection();
        $this->redisConnection->shouldReceive('pack')->andReturnUsing(fn (array $values): array => $values);
        $this->redisConnection->shouldReceive('eval')->andReturn(1);

        $this->assertFalse($this->app->make(Synchroniser::class)->pull(Module::Supplier)->skipped);
    }

    public function testTheLockIsTakenOnTheRedisConnectionNotInTheProcess(): void
    {
        $this->redisProxy->expects('set')
            ->with($this->prefixed(self::LOCK), m::type('string'), 'EX', 60, 'NX')
            ->andReturnTrue();

        $this->assertTrue(Cache::lock(self::LOCK, 60)->get());
    }

    /**
     * The key a lock is stored under: the cache store's prefix, then the lock's name.
     */
    private function prefixed(string $name): string
    {
        return $this->app->make('config')->string('cache.prefix') . $name;
    }
}
