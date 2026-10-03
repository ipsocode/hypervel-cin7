<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Connector;

use Hypervel\Saloon\Exceptions\Request\ClientException;
use Hypervel\Saloon\Exceptions\Request\ServerException;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * The bounded 503 retry. `Sleep::fake()` skips the real waits and records each
 * delay so the configured gap can be asserted.
 */
class RetryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Sleep::fake();
    }

    protected function tearDown(): void
    {
        Sleep::fake(false);

        parent::tearDown();
    }

    public function testA503IsRetriedUpToTheConfiguredAttemptCount(): void
    {
        $mock = Saloon::fake(array_fill(0, 4, Cin7Fake::throttled()));

        try {
            $this->connector()->send(new GetCustomer);
            $this->fail('The exhausted retry should have surfaced a ServerException.');
        } catch (ServerException $exception) {
            $this->assertSame(503, $exception->status());
        }

        // Four attempts, so three gaps between them.
        $mock->assertSentCount(4);
        Sleep::assertSequence([
            Sleep::usleep(5_000_000),
            Sleep::usleep(5_000_000),
            Sleep::usleep(5_000_000),
        ]);
    }

    public function testA503ThatClearsReturnsTheEventualSuccess(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::throttled(),
            Cin7Fake::list('CustomerList', [Cin7Payloads::customer()]),
        ]);

        $response = $this->connector()->send(new GetCustomer);

        $this->assertSame(200, $response->status());
        $this->assertCount(1, $response->json('CustomerList'));
        $mock->assertSentCount(2);
        Sleep::assertSleptTimes(1);
    }

    /**
     * 429 is the status Cin7 documents for its 60 calls per minute limit.
     */
    public function testA429IsRetriedLikeA503(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::limitReached(),
            Cin7Fake::list('CustomerList', [Cin7Payloads::customer()]),
        ]);

        $response = $this->connector()->send(new GetCustomer);

        $this->assertSame(200, $response->status());
        $mock->assertSentCount(2);
        Sleep::assertSequence([Sleep::usleep(5_000_000)]);
    }

    public function testAClientErrorIsNotRetried(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::error('Request is invalid'),
        ]);

        try {
            $this->connector()->send(new GetCustomer);
            $this->fail('A 400 should have surfaced a ClientException.');
        } catch (ClientException $exception) {
            $this->assertSame(400, $exception->status());
        }

        $mock->assertSentCount(1);
        Sleep::assertNeverSlept();
    }

    /**
     * Cin7 answers a bad credential with `403 Incorrect credentials!`.
     */
    public function testARejectedCredentialIsNotRetried(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::credentialsRejected(),
        ]);

        $this->expectException(ClientException::class);

        try {
            $this->connector()->send(new GetCustomer);
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    public function testA500IsNotRetried(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::error('boom', 500),
        ]);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send(new GetCustomer);
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    public function testASuccessIsNotSleptOn(): void
    {
        $mock = Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->connector()->send(new GetCustomer);

        $mock->assertSentCount(1);
        Sleep::assertNeverSlept();
    }

    public function testTheAttemptCountAndDelayAreConfigurable(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 2);
        $this->app->get('config')->set('cin7.retry.delay_ms', 250);

        $mock = Saloon::fake(array_fill(0, 2, Cin7Fake::throttled()));

        // The retry policy is read at construction, so config must be set first.
        $request = new GetCustomer;

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(2);
            Sleep::assertSequence([Sleep::usleep(250_000)]);
        }
    }

    public function testANonPositiveAttemptCountStillMakesOneAttempt(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 0);

        $mock = Saloon::fake([Cin7Fake::throttled()]);

        $request = new GetCustomer;

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    /**
     * The delay clamps to 0 ms, so the retry runs back-to-back without sleeping.
     */
    public function testANegativeDelayIsClampedRatherThanRejected(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 2);
        $this->app->get('config')->set('cin7.retry.delay_ms', -1);

        $mock = Saloon::fake(array_fill(0, 2, Cin7Fake::throttled()));

        $request = new GetCustomer;

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(2);
            Sleep::assertNeverSlept();
        }
    }

    public function testAnAbsentRetryConfigFallsBackToThePackagedDefaults(): void
    {
        $this->app->get('config')->set('cin7.retry.times', null);
        $this->app->get('config')->set('cin7.retry.delay_ms', null);

        $mock = Saloon::fake(array_fill(0, 4, Cin7Fake::throttled()));

        $request = new GetCustomer;

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(4);
            Sleep::assertSleptTimes(3);
        }
    }
}
