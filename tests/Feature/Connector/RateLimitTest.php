<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Connector;

use Hypervel\RateLimiter\Limit;
use Hypervel\RateLimiter\RateLimiter;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * Rate limiting cannot be observed through a faked send: the manager enforces
 * limits only when no fake response matched, and records cooldowns only for
 * responses that came off the wire. Both hooks are therefore driven directly —
 * which is also what pins the trait-shadowing fix on
 * `resolveRateLimitCooldown()`, a bug no mocked test would ever catch.
 */
class RateLimitTest extends TestCase
{
    public function testTheConnectorDeclaresRateLimitsAndWaitsForThem(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertTrue($connector->usesRateLimits());
        $this->assertTrue($connector->shouldWaitForRateLimits());
    }

    public function testItPublishesOneSharedPolicyKeyedByTheAccount(): void
    {
        $connector = new Cin7Connector('acct', 'key', 60, 60);

        $policies = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector));

        $this->assertCount(1, $policies);

        $policy = $policies[0];

        $this->assertInstanceOf(Limit::class, $policy);
        $this->assertSame(60, $policy->maxAttempts);
        $this->assertSame(60, $policy->decaySeconds);
        $this->assertSame('cin7:api:acct', $policy->key);
    }

    /**
     * Cin7 meters the account, not the endpoint, so every request has to land
     * on the same key — a per-endpoint key would let eleven endpoints each burn
     * a full window.
     */
    public function testEveryEndpointSharesTheSameLimiterKey(): void
    {
        $connector = new Cin7Connector('acct', 'key', 60, 60);

        foreach (Endpoint::cases() as $endpoint) {
            $policies = $connector->resolveRateLimitPolicies(
                $this->pendingRequestFor($connector, new ListRecords($endpoint)),
            );

            $this->assertSame('cin7:api:acct', $policies[0]->key, $endpoint->value);
        }
    }

    /**
     * A shared (redis/database) limiter store would otherwise let two Cin7
     * accounts throttle each other on the same bucket.
     */
    public function testDifferentAccountsGetDistinctLimiterKeys(): void
    {
        $connectorA = new Cin7Connector('acct-a', 'key', 60, 60);
        $connectorB = new Cin7Connector('acct-b', 'key', 60, 60);

        $policyA = $connectorA->resolveRateLimitPolicies($this->pendingRequestFor($connectorA))[0];
        $policyB = $connectorB->resolveRateLimitPolicies($this->pendingRequestFor($connectorB))[0];

        $this->assertSame('cin7:api:acct-a', $policyA->key);
        $this->assertSame('cin7:api:acct-b', $policyB->key);
        $this->assertNotSame($policyA->key, $policyB->key);
    }

    public function testANullStoreIsTheDefault(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertNull($connector->resolveRateLimitStoreName());
    }

    /**
     * A configured store lets consumers share the limiter window across
     * workers and servers instead of the framework's per-worker default.
     */
    public function testAConfiguredStoreIsReturned(): void
    {
        $connector = new Cin7Connector('acct', 'key', 60, 60, 'redis');

        $this->assertSame('redis', $connector->resolveRateLimitStoreName());
    }

    public function testANonPositiveMaxOrPeriodDisablesThrottling(): void
    {
        foreach ([[0, 60], [-1, 60], [60, 0], [60, -1]] as [$max, $period]) {
            $connector = new Cin7Connector('acct', 'key', $max, $period);

            $this->assertSame(
                [],
                $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector)),
                "Expected [{$max}/{$period}] to disable throttling.",
            );
        }
    }

    /**
     * The policy is only useful if the framework limiter can actually deny on
     * it. Consume it to exhaustion against the real (worker-array) store.
     *
     * The manager's wait loop itself is not driven here: it spins until the
     * window frees up, and a faked Sleep never advances the store's clock.
     * `shouldWaitForRateLimits()` above is what pins wait-instead-of-throw.
     */
    public function testThePolicyIsEnforceableByTheFrameworkLimiter(): void
    {
        $connector = new Cin7Connector('acct', 'key', 2, 60);
        $policy = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector))[0];

        $limiter = $this->app->make(RateLimiter::class)->store();
        $name = 'saloon:' . $connector::class;

        $this->assertTrue($limiter->consume($policy, $name)->allowed());
        $this->assertTrue($limiter->consume($policy, $name)->allowed());

        $denied = $limiter->consume($policy, $name);

        $this->assertTrue($denied->denied());
        $this->assertGreaterThan(0, $denied->retryAfter());

        $limiter->clear($policy, $name);
    }

    /**
     * The trait default keys the cooldown on `static::class` alone, which
     * would let a 503 from one Cin7 account impose its cooldown on every
     * other account sharing the same store.
     */
    public function testDifferentAccountsGetDistinctCooldownKeys(): void
    {
        $connectorA = new Cin7Connector('acct-a', 'key');
        $connectorB = new Cin7Connector('acct-b', 'key');

        $keyA = $connectorA->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connectorA));
        $keyB = $connectorB->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connectorB));

        $this->assertSame(Cin7Connector::class . ':acct-a', $keyA);
        $this->assertSame(Cin7Connector::class . ':acct-b', $keyB);
        $this->assertNotSame($keyA, $keyB);
    }

    public function testA503ImposesAFiveSecondCooldown(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertSame(5, $connector->resolveRateLimitCooldownFor($this->responseWithStatus(503)));
    }

    public function testOtherStatusesImposeNoCooldown(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        // Notably 200: the manager runs this hook for every response off the
        // wire, so anything but 503 must return null.
        $this->assertNull($connector->resolveRateLimitCooldownFor($this->responseWithStatus(200)));
        $this->assertNull($connector->resolveRateLimitCooldownFor($this->responseWithStatus(429)));
        $this->assertNull($connector->resolveRateLimitCooldownFor($this->responseWithStatus(500)));
    }

    /**
     * The connector built from config throttles on the same defaults as one
     * built by hand — the arguments the provider passes are the ones the
     * constructor defaults document.
     */
    public function testTheContainerConnectorThrottlesOnTheConfiguredDefaults(): void
    {
        $connector = $this->connector();

        $policies = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector));

        $this->assertCount(1, $policies);
        $this->assertSame(60, $policies[0]->maxAttempts);
        $this->assertSame(60, $policies[0]->decaySeconds);
    }

    /**
     * The provider passes `cin7.rate_limit.store` straight through, so a
     * consumer publishing a shared store sees it on the resolved connector.
     */
    public function testTheContainerConnectorPicksUpAConfiguredStore(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.store', 'redis');

        $this->assertSame('redis', $this->connector()->resolveRateLimitStoreName());
    }

    /**
     * Build a real Saloon Response carrying the given status.
     */
    private function responseWithStatus(int $status): Response
    {
        // One attempt only — otherwise a 503 would exhaust the retry policy
        // (and the mock sequence) before this returns.
        $this->app->get('config')->set('cin7.retry.times', 1);

        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::throttled(), $status)]);
        $connector = $this->connector();

        try {
            return $connector->send(new ListRecords(Endpoint::Customer));
        } catch (RequestException $exception) {
            return $exception->response();
        } finally {
            $mock->assertSentCount(1);
            Saloon::clearFake();
        }
    }
}
