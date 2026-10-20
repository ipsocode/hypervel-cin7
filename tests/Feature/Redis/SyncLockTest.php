<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\Foundation\Testing\RefreshDatabase;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Facades\Cache;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Synchroniser;
use Ipsocode\Cin7\Testing\Cin7Fake;

/**
 * The sync's module lock lives in the shared cache store, so a pull on one worker holds off a pull
 * of the same module on another. Each test drops the cache manager's store between the two, which
 * is what a second worker has: its own connection and nothing of the first one's memory.
 */
class SyncLockTest extends RedisTestCase
{
    use RefreshDatabase;

    private const LOCK = 'cin7:sync:acct-test:customer';

    public function testALockTakenByAnotherWorkerMakesThePullSkipTheModule(): void
    {
        $mock = Saloon::fake([]);

        $this->assertTrue(Cache::lock(self::LOCK, 60)->get());
        Cache::forgetDriver();

        $result = $this->app->make(Synchroniser::class)->pull(Module::Customer);

        $this->assertTrue($result->skipped);
        $this->assertFalse($result->failed());
        $mock->assertNothingSent();
    }

    public function testThePullReleasesItsLockForAnotherWorker(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->app->make(Synchroniser::class)->pull(Module::Customer);
        Cache::forgetDriver();

        $this->assertTrue(Cache::lock(self::LOCK, 60)->get());
    }

    public function testTheLockOfOneModuleDoesNotHoldBackAnother(): void
    {
        Saloon::fake([Cin7Fake::list('SupplierList')]);

        $this->assertTrue(Cache::lock(self::LOCK, 60)->get());
        Cache::forgetDriver();

        $this->assertFalse($this->app->make(Synchroniser::class)->pull(Module::Supplier)->skipped);
    }

    public function testTheLockIsHeldInRedisNotInTheProcess(): void
    {
        Cache::lock(self::LOCK, 60)->get();

        $this->assertNotEmpty($this->redisClientWithoutPrefix()->keys('*'));
    }
}
