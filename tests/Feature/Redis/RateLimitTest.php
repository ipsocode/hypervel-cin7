<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Redis;

use Hypervel\RateLimiter\AdmissionPolicy;
use Hypervel\RateLimiter\Cooldown;
use Hypervel\RateLimiter\Limiter;
use Hypervel\RateLimiter\RateLimiter;
use Ipsocode\Cin7\Cin7Connector;

/**
 * The window and the cooldown are keyed by the Cin7 application and kept in the shared limiter
 * store, so every worker that sends the same key sees one of each. The `Redis` facade is mocked, so
 * each test scripts the replies the limiter's Lua scripts would give and asserts the keys and
 * arguments that reach the connection.
 */
class RateLimitTest extends RedisTestCase
{
    /**
     * Every script run the connection received, as `[script, key, arguments]`.
     *
     * @var list<array{string, string, list<string>}>
     */
    private array $runs = [];

    public function testTwoWorkersShareOneWindow(): void
    {
        $connector = new Cin7Connector('acct', 'key', 2, 60);
        $policy = $this->policyOf($connector);

        $this->scriptReplies([[1, 2, 1, 0, 0], [1, 2, 0, 0, 0], [0, 2, 0, 1_000_000, 1_000_000]]);

        $this->assertTrue($this->limiter()->consume($policy)->allowed());
        $this->assertSame(0, $this->limiter()->consume($policy)->remaining());
        $this->assertTrue($this->limiter()->consume($policy)->denied());

        $this->assertCount(3, $this->runs);
        $this->assertCount(1, array_unique(array_column($this->runs, 1)));
        $this->assertSame(['consume', '1', '2', '60000'], $this->runs[0][2]);
    }

    public function testTwoConnectorsWithTheSameAccountAndKeyShareOneWindow(): void
    {
        $this->scriptReplies([[1, 1, 0, 0, 0], [0, 1, 0, 1_000_000, 1_000_000]]);

        $this->assertTrue($this->limiter()->consume($this->policyOf(new Cin7Connector('acct', 'key', 1, 60)))->allowed());
        $this->assertTrue($this->limiter()->consume($this->policyOf(new Cin7Connector('acct', 'key', 1, 60)))->denied());

        $this->assertSame($this->runs[0][1], $this->runs[1][1]);
    }

    public function testDifferentKeysAndAccountsGetTheirOwnWindows(): void
    {
        $this->scriptReplies(array_fill(0, 3, [1, 1, 0, 0, 0]));

        foreach ([['acct', 'key'], ['acct', 'other-key'], ['other-acct', 'key']] as [$account, $key]) {
            $this->assertTrue($this->limiter()->consume($this->policyOf(new Cin7Connector($account, $key, 1, 60)))->allowed());
        }

        $this->assertCount(3, array_unique(array_column($this->runs, 1)));
    }

    public function testACooldownRecordedByOneWorkerIsSeenByAnother(): void
    {
        $connector = new Cin7Connector('acct', 'key');
        $key = $connector->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connector));

        $this->scriptReplies([[0, 0, 0, 30_000_000, 0], [0, 0, 0, 29_000_000, 0]]);

        $this->assertTrue($this->limiter()->block(Cooldown::for($key), 30)->denied());

        $result = $this->limiter()->inspect(Cooldown::for($key));

        $this->assertTrue($result->denied());
        $this->assertGreaterThan(0, $result->retryAfter());

        $this->assertSame(['block', '30000000'], $this->runs[0][2]);
        $this->assertSame(['inspect', '0'], $this->runs[1][2]);
        $this->assertSame($this->runs[0][1], $this->runs[1][1]);
    }

    public function testACooldownOnOneKeyDoesNotCoolAnotherApplicationDown(): void
    {
        $connector = new Cin7Connector('acct', 'key');
        $other = new Cin7Connector('acct', 'other-key');

        $this->scriptReplies([[0, 0, 0, 30_000_000, 0], [1, 0, 0, 0, 0]]);

        $this->limiter()->block(Cooldown::for($connector->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connector))), 30);

        $this->assertTrue($this->limiter()->inspect(Cooldown::for($other->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($other))))->allowed());

        $this->assertNotSame($this->runs[0][1], $this->runs[1][1]);
    }

    public function testTheWindowIsHeldInRedisNotInTheProcess(): void
    {
        $this->scriptReplies([[1, 1, 0, 0, 0]]);

        $this->limiter()->consume($this->policyOf(new Cin7Connector('acct', 'key', 1, 60)));

        $this->assertCount(1, $this->runs);
    }

    /**
     * Answer the next script runs with the given tuples, in order, and record each run in `$runs`.
     *
     * @param list<list<int>> $replies
     */
    private function scriptReplies(array $replies): void
    {
        $this->lendConnection();
        $this->redisConnection->shouldReceive('evalWithShaCache')
            ->times(count($replies))
            ->andReturnUsing(function (string $script, array $keys, array $arguments) use (&$replies): array {
                $this->runs[] = [$script, $keys[0], $arguments];

                return array_shift($replies);
            });
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
