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
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * The manager enforces limits only when no fake matched and records cooldowns
 * only for wire responses, so these tests drive the hooks directly.
 */
class RateLimitTest extends TestCase
{
    public function testTheConnectorDeclaresRateLimitsAndWaitsForThem(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertTrue($connector->usesRateLimits());
        $this->assertTrue($connector->shouldWaitForRateLimits());
    }

    public function testItPublishesOneSharedPolicyKeyedByTheApplication(): void
    {
        $connector = new Cin7Connector('acct', 'key', 60, 60);

        $policies = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector));

        $this->assertCount(1, $policies);

        $policy = $policies[0];

        $this->assertInstanceOf(Limit::class, $policy);
        $this->assertSame(60, $policy->maxAttempts);
        $this->assertSame(60, $policy->decaySeconds);
        $this->assertSame(self::limiterKey('acct'), $policy->key);
    }

    public function testEveryRequestSharesTheSameLimiterKey(): void
    {
        $connector = new Cin7Connector('acct', 'key', 60, 60);

        foreach ([new GetCustomer, new PostCustomer, new PutCustomer] as $request) {
            $policies = $connector->resolveRateLimitPolicies(
                $this->pendingRequestFor($connector, $request),
            );

            $this->assertSame(self::limiterKey('acct'), $policies[0]->key, $request::class);
        }
    }

    public function testDifferentAccountsGetDistinctLimiterKeys(): void
    {
        $connectorA = new Cin7Connector('acct-a', 'key', 60, 60);
        $connectorB = new Cin7Connector('acct-b', 'key', 60, 60);

        $policyA = $connectorA->resolveRateLimitPolicies($this->pendingRequestFor($connectorA))[0];
        $policyB = $connectorB->resolveRateLimitPolicies($this->pendingRequestFor($connectorB))[0];

        $this->assertSame(self::limiterKey('acct-a'), $policyA->key);
        $this->assertSame(self::limiterKey('acct-b'), $policyB->key);
        $this->assertNotSame($policyA->key, $policyB->key);
    }

    /**
     * Cin7 applies its limit per API application, so two applications of one account throttle
     * apart, and the key carries a digest of the application key, never the key.
     */
    public function testDifferentApplicationsOfOneAccountGetDistinctLimiterKeys(): void
    {
        $connectorA = new Cin7Connector('acct', 'key-a', 60, 60);
        $connectorB = new Cin7Connector('acct', 'key-b', 60, 60);

        $policyA = $connectorA->resolveRateLimitPolicies($this->pendingRequestFor($connectorA))[0];
        $policyB = $connectorB->resolveRateLimitPolicies($this->pendingRequestFor($connectorB))[0];

        $this->assertSame(self::limiterKey('acct', 'key-a'), $policyA->key);
        $this->assertNotSame($policyA->key, $policyB->key);
        $this->assertStringNotContainsString('key-a', $policyA->key);
    }

    public function testANullStoreIsTheDefault(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertNull($connector->resolveRateLimitStoreName());
    }

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
     * Consumes the policy to exhaustion on the real limiter store. The wait loop
     * is not driven: a faked Sleep never advances the store's clock.
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

    public function testTheCooldownIsKeyedLikeTheWindow(): void
    {
        $connectorA = new Cin7Connector('acct-a', 'key');
        $connectorB = new Cin7Connector('acct-b', 'key');

        $keyA = $connectorA->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connectorA));
        $keyB = $connectorB->resolveRateLimitCooldownKeyFor($this->pendingRequestFor($connectorB));

        $this->assertSame(self::limiterKey('acct-a'), $keyA);
        $this->assertSame(self::limiterKey('acct-b'), $keyB);
        $this->assertNotSame($keyA, $keyB);
    }

    public function testA503ImposesAFiveSecondCooldown(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertSame(5, $connector->resolveRateLimitCooldownFor($this->responseWithStatus(503)));
    }

    /**
     * 429 is the status Cin7 documents for its limit; the trait's parser reads its `Retry-After`.
     */
    public function testA429CoolsDownForItsRetryAfter(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertSame(30, $connector->resolveRateLimitCooldownFor($this->responseWithStatus(429, ['Retry-After' => '30'])));
    }

    public function testA429WithoutRetryAfterImposesAFiveSecondCooldown(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        $this->assertSame(Cin7Connector::THROTTLE_COOLDOWN, $connector->resolveRateLimitCooldownFor($this->responseWithStatus(429)));
        $this->assertSame(5, Cin7Connector::THROTTLE_COOLDOWN);
    }

    public function testOtherStatusesImposeNoCooldown(): void
    {
        $connector = new Cin7Connector('acct', 'key');

        // The manager runs this hook for every wire response, 200s included.
        $this->assertNull($connector->resolveRateLimitCooldownFor($this->responseWithStatus(200)));
        $this->assertNull($connector->resolveRateLimitCooldownFor($this->responseWithStatus(500)));
    }

    public function testTheContainerConnectorThrottlesOnTheConfiguredDefaults(): void
    {
        $connector = $this->connector();

        $policies = $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector));

        $this->assertCount(1, $policies);
        $this->assertSame(60, $policies[0]->maxAttempts);
        $this->assertSame(60, $policies[0]->decaySeconds);
    }

    public function testTheContainerConnectorPicksUpAConfiguredStore(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.store', 'redis');

        $this->assertSame('redis', $this->connector()->resolveRateLimitStoreName());
    }

    /**
     * The logical key the connector throttles on: the account and a short digest of the
     * application key.
     */
    private static function limiterKey(string $accountId, string $applicationKey = 'key'): string
    {
        return 'cin7:api:' . $accountId . ':' . substr(hash('sha256', $applicationKey), 0, 16);
    }

    /**
     * @param array<string, string> $headers
     */
    private function responseWithStatus(int $status, array $headers = []): Response
    {
        // One attempt only, or a 429 or 503 would retry past the single mocked response.
        $this->app->get('config')->set('cin7.retry.times', 1);

        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::throttled(), $status, $headers)]);
        $connector = $this->connector();

        try {
            return $connector->send(new GetCustomer);
        } catch (RequestException $exception) {
            return $exception->response();
        } finally {
            $mock->assertSentCount(1);
            Saloon::clearFake();
        }
    }
}
