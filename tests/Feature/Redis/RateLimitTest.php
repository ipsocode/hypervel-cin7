<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\RateLimiter\AdmissionPolicy;
use Hypervel\RateLimiter\Cooldown;
use Hypervel\RateLimiter\Limiter;
use Hypervel\RateLimiter\RateLimiter;
use Ipsocode\Cin7\Cin7Connector;

/**
 * The window and the cooldown are keyed by the Cin7 application and live in the shared limiter
 * store, so every worker sees one of each. Each test forgets the limiter's store between the two
 * sides, which is what a second worker has: its own connection and nothing of the first one's memory.
 */
class RateLimitTest extends RedisTestCase
{
    public function testTwoWorkersShareOneWindow(): void
    {
        $connector = new Cin7Connector('acct', 'key', 2, 60);
        $policy = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector))[0];

        $this->assertTrue($this->limiter()->consume($policy)->allowed());

        $this->app->make(RateLimiter::class)->forgetInstance('redis');

        $this->assertSame(0, $this->limiter()->consume($policy)->remaining());

        $this->app->make(RateLimiter::class)->forgetInstance('redis');

        $this->assertTrue($this->limiter()->consume($policy)->denied());
    }

    public function testTwoConnectorsWithTheSameAccountAndKeyShareOneWindow(): void
    {
        $first = new Cin7Connector('acct', 'key', 1, 60);
        $second = new Cin7Connector('acct', 'key', 1, 60);

        $this->assertTrue($this->limiter()->consume($this->policyOf($first))->allowed());
        $this->assertTrue($this->limiter()->consume($this->policyOf($second))->denied());
    }

    public function testDifferentKeysAndAccountsGetTheirOwnWindows(): void
    {
        $connector = new Cin7Connector('acct', 'key', 1, 60);

        $this->assertTrue($this->limiter()->consume($this->policyOf($connector))->allowed());
        $this->assertTrue($this->limiter()->consume($this->policyOf(new Cin7Connector('acct', 'other-key', 1, 60)))->allowed());
        $this->assertTrue($this->limiter()->consume($this->policyOf(new Cin7Connector('other-acct', 'key', 1, 60)))->allowed());
    }

    public function testACooldownRecordedByOneWorkerIsSeenByAnother(): void
    {
        $connector = new Cin7Connector('acct', 'key');
        $key = $connector->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connector));

        $this->assertTrue($this->limiter()->block(Cooldown::for($key), 30)->denied());

        $this->app->make(RateLimiter::class)->forgetInstance('redis');

        $result = $this->limiter()->inspect(Cooldown::for($key));

        $this->assertTrue($result->denied());
        $this->assertGreaterThan(0, $result->retryAfter());
    }

    public function testACooldownOnOneKeyDoesNotCoolAnotherApplicationDown(): void
    {
        $connector = new Cin7Connector('acct', 'key');
        $other = new Cin7Connector('acct', 'other-key');

        $this->limiter()->block(Cooldown::for($connector->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connector))), 30);

        $this->assertTrue($this->limiter()->inspect(Cooldown::for($other->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($other))))->allowed());
    }

    public function testTheWindowIsHeldInRedisNotInTheProcess(): void
    {
        $this->limiter()->consume($this->policyOf(new Cin7Connector('acct', 'key', 1, 60)));

        $this->assertNotEmpty($this->redisClientWithoutPrefix()->keys('*'));
    }

    private function limiter(): Limiter
    {
        return $this->app->make(RateLimiter::class)->store('redis');
    }

    private function policyOf(Cin7Connector $connector): AdmissionPolicy
    {
        return $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector))[0];
    }
}
