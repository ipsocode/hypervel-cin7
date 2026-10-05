<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Provider;

use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Facades\Saloon;
use InvalidArgumentException;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Cin7Manager;
use Ipsocode\Cin7\Cin7ServiceProvider;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\TestCase;

class Cin7ManagerTest extends TestCase
{
    public function testTheManagerIsASingleton(): void
    {
        $this->assertSame($this->manager(), $this->manager());
    }

    public function testTheSameNameReturnsTheSameInstance(): void
    {
        $this->useSandbox();

        $this->assertSame($this->manager()->connection('sandbox'), $this->manager()->connection('sandbox'));
        $this->assertSame($this->manager()->connection(), $this->manager()->connection('default'));
    }

    public function testDistinctNamesReturnDistinctConnectorsWithDistinctLimiterKeys(): void
    {
        $this->useSandbox();

        $default = $this->manager()->connection();
        $sandbox = $this->manager()->connection('sandbox');

        $this->assertNotSame($default, $sandbox);
        $this->assertSame('acct-test', $default->accountId());
        $this->assertSame('acct-sandbox', $sandbox->accountId());
        $this->assertSame('key-sandbox', $sandbox->headers()['api-auth-applicationkey']);
        $this->assertNotSame($this->limiterKey($default), $this->limiterKey($sandbox));
        $this->assertSame(
            'cin7:api:acct-sandbox:' . substr(hash('sha256', 'key-sandbox'), 0, 16),
            $this->limiterKey($sandbox),
        );
    }

    public function testTheConnectorTypeResolvesTheDefaultConnection(): void
    {
        $this->assertSame($this->manager()->connection(), $this->connector());

        $this->useSandbox();
        $this->app->get('config')->set('cin7.default', 'sandbox');
        $this->app->forgetInstance(Cin7Manager::class);

        $this->assertSame('sandbox', $this->manager()->defaultConnectionName());
        $this->assertSame('acct-sandbox', $this->app->make(Cin7Connector::class)->accountId());
        $this->assertSame($this->manager()->connection('sandbox'), $this->connector());
    }

    public function testTheDefaultNameIsDefaultUnlessConfigured(): void
    {
        $this->assertSame('default', $this->manager()->defaultConnectionName());
    }

    public function testAnUnknownNameThrowsNamingTheKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cin7 connection [nope] is not configured');

        $this->manager()->connection('nope');
    }

    public function testAConnectionThatIsNotAnArrayIsNotConfigured(): void
    {
        $this->app->get('config')->set('cin7.connections.broken', 'acct');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cin7 connection [broken] is not configured');

        $this->manager()->connection('broken');
    }

    public function testAConnectionWithoutRateLimitInheritsTheTopLevelValues(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.max', '25');
        $this->app->get('config')->set('cin7.rate_limit.period', '20');
        $this->useSandbox();

        $policy = $this->policy($this->manager()->connection('sandbox'));

        $this->assertSame(25, $policy->maxAttempts);
        $this->assertSame(20, $policy->decaySeconds);
    }

    public function testAConnectionKeepsTheTopLevelValuesItLeavesOut(): void
    {
        $this->useSandbox(['max' => '10']);

        $policy = $this->policy($this->manager()->connection('sandbox'));

        $this->assertSame(10, $policy->maxAttempts);
        $this->assertSame(60, $policy->decaySeconds);
    }

    public function testAConnectionsOwnRateLimitWinsAndIsCast(): void
    {
        $this->useSandbox(['max' => '30', 'period' => '15', 'store' => 'redis']);

        $policy = $this->policy($this->manager()->connection('sandbox'));

        $this->assertSame(30, $policy->maxAttempts);
        $this->assertSame(15, $policy->decaySeconds);
        $this->assertSame('redis', $this->manager()->connection('sandbox')->resolveRateLimitStoreName());
        $this->assertNull($this->manager()->connection()->resolveRateLimitStoreName());
    }

    public function testTheCooldownIsInheritedOrOverriddenPerConnection(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.cooldown', '9');
        $this->useSandbox(['cooldown' => '2']);
        $this->app->get('config')->set('cin7.connections.plain', [
            'account_id' => 'acct-plain',
            'application_key' => 'key-plain',
        ]);

        $this->assertSame(2, $this->cooldown('sandbox'));
        $this->assertSame(9, $this->cooldown('plain'));
        $this->assertSame(9, $this->cooldown(null));

        $this->app->get('config')->set('cin7.rate_limit.cooldown', null);
        $this->app->forgetInstance(Cin7Manager::class);

        $this->assertSame(Cin7Connector::THROTTLE_COOLDOWN, $this->cooldown('plain'));
    }

    public function testAConnectionInheritsTheTopLevelStoreOrOverridesItWithNull(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.store', 'redis');
        $this->useSandbox();
        $this->app->get('config')->set('cin7.connections.local', [
            'account_id' => 'acct-local',
            'application_key' => 'key-local',
            'rate_limit' => ['store' => null],
        ]);

        $this->assertSame('redis', $this->manager()->connection('sandbox')->resolveRateLimitStoreName());
        $this->assertNull($this->manager()->connection('local')->resolveRateLimitStoreName());
    }

    public function testUnsetCredentialsAreCastRatherThanTrusted(): void
    {
        $this->app->get('config')->set('cin7.connections.blank', ['account_id' => null]);

        $headers = $this->manager()->connection('blank')->headers();

        $this->assertSame('', $headers['api-auth-accountid']);
        $this->assertSame('', $headers['api-auth-applicationkey']);
    }

    public function testAZeroMaxOnOneConnectionLeavesTheOthersWindowAlone(): void
    {
        $this->useSandbox(['max' => 0]);

        $this->assertSame([], $this->manager()->connection('sandbox')->resolveRateLimitPolicies(
            $this->pendingRequestFor($this->manager()->connection('sandbox')),
        ));
        $this->assertSame(60, $this->policy($this->manager()->connection())->maxAttempts);
    }

    /**
     * `mergeConfigFrom()` is shallow, so a published `connections` array wins as a whole, as
     * `rate_limit` and `retry` do.
     */
    public function testAPublishedConnectionsArrayWinsAsAWhole(): void
    {
        $this->useSandbox();

        (new Cin7ServiceProvider($this->app))->register();

        $this->assertSame(['sandbox'], array_keys(config('cin7.connections')));
    }

    private function manager(): Cin7Manager
    {
        return $this->app->make(Cin7Manager::class);
    }

    /**
     * @param null|array<string, mixed> $rateLimit
     */
    private function useSandbox(?array $rateLimit = null): void
    {
        $this->app->get('config')->set('cin7.connections', [
            'sandbox' => array_filter([
                'account_id' => 'acct-sandbox',
                'application_key' => 'key-sandbox',
                'rate_limit' => $rateLimit,
            ], fn (mixed $value): bool => $value !== null),
        ]);
    }

    private function cooldown(?string $name): ?int
    {
        $connector = $this->manager()->connection($name);

        // One attempt only, or the 503 would retry past the single mocked response.
        $this->app->get('config')->set('cin7.retry.times', 1);
        Saloon::fake([Cin7Fake::throttled()]);

        try {
            $response = $connector->send(new GetCustomer);
        } catch (RequestException $exception) {
            $response = $exception->response();
        }

        return $connector->resolveRateLimitCooldownFor($response);
    }

    private function policy(Cin7Connector $connector): object
    {
        return $connector->resolveRateLimitPolicies($this->pendingRequestFor($connector))[0];
    }

    private function limiterKey(Cin7Connector $connector): string
    {
        return $this->policy($connector)->key;
    }
}
