<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Connector;

use Hypervel\Saloon\Exceptions\Request\ClientException;
use Hypervel\Saloon\Exceptions\Request\ServerException;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * The bounded 503 retry that replaces upstream's unbounded recursion.
 *
 * `Sleep::fake()` is what makes this runnable: the real policy waits five
 * seconds between attempts, so an honest four-attempt test would take fifteen.
 * Faking it also turns the delays into assertable values, which is the only way
 * to prove the configured gap is the one actually used.
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
        $mock = Saloon::fake(array_fill(0, 4, MockResponse::make(Cin7Payloads::throttled(), 503)));

        try {
            $this->connector()->send(new ListRecords(Endpoint::Customer));
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
            MockResponse::make(Cin7Payloads::throttled(), 503),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer()])),
        ]);

        $response = $this->connector()->send(new ListRecords(Endpoint::Customer));

        $this->assertSame(200, $response->status());
        $this->assertCount(1, $response->json('CustomerList'));
        $mock->assertSentCount(2);
        Sleep::assertSleptTimes(1);
    }

    public function testAClientErrorIsNotRetried(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::error('Request is invalid'), 400),
        ]);

        try {
            $this->connector()->send(new ListRecords(Endpoint::Customer));
            $this->fail('A 400 should have surfaced a ClientException.');
        } catch (ClientException $exception) {
            $this->assertSame(400, $exception->status());
        }

        $mock->assertSentCount(1);
        Sleep::assertNeverSlept();
    }

    /**
     * `403 Incorrect credentials!` is the shape a bad Cin7 credential takes —
     * observed, not assumed — and it is the one a misconfigured deployment hits
     * on every call. Retrying it four times with a five second gap would turn a
     * config mistake into a twenty second hang.
     */
    public function testARejectedCredentialIsNotRetried(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::error('Incorrect credentials!'), 403),
        ]);

        $this->expectException(ClientException::class);

        try {
            $this->connector()->send(new ListRecords(Endpoint::Customer));
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    public function testA500IsNotRetried(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::error('boom'), 500),
        ]);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send(new ListRecords(Endpoint::Customer));
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    public function testASuccessIsNotSleptOn(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->connector()->send(new ListRecords(Endpoint::Customer));

        $mock->assertSentCount(1);
        Sleep::assertNeverSlept();
    }

    public function testTheAttemptCountAndDelayAreConfigurable(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 2);
        $this->app->get('config')->set('cin7.retry.delay_ms', 250);

        $mock = Saloon::fake(array_fill(0, 2, MockResponse::make(Cin7Payloads::throttled(), 503)));

        // The policy is read when the request is constructed, so the config
        // above has to be in place before this line — not before send().
        $request = new ListRecords(Endpoint::Customer);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(2);
            Sleep::assertSequence([Sleep::usleep(250_000)]);
        }
    }

    /**
     * `times` is clamped to at least one attempt: a published config with
     * `CIN7_RETRY_TIMES=0` must still make the call rather than silently
     * returning nothing, and Saloon would reject a zero outright.
     */
    public function testANonPositiveAttemptCountStillMakesOneAttempt(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 0);

        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::throttled(), 503)]);

        $request = new ListRecords(Endpoint::Customer);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(1);
            Sleep::assertNeverSlept();
        }
    }

    /**
     * The delay is clamped the same way, and here the clamp is load-bearing:
     * Saloon's RetryPolicy throws an InvalidArgumentException on a negative
     * delay, so an unclamped `CIN7_RETRY_DELAY_MS=-1` would take down every
     * request at construction rather than merely retrying too fast. Clamped, it
     * retries back-to-back — the manager skips the sleep entirely at zero.
     */
    public function testANegativeDelayIsClampedRatherThanRejected(): void
    {
        $this->app->get('config')->set('cin7.retry.times', 2);
        $this->app->get('config')->set('cin7.retry.delay_ms', -1);

        $mock = Saloon::fake(array_fill(0, 2, MockResponse::make(Cin7Payloads::throttled(), 503)));

        $request = new ListRecords(Endpoint::Customer);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(2);
            Sleep::assertNeverSlept();
        }
    }

    /**
     * A missing key falls back to the packaged default rather than to zero —
     * `config()` returns null for an absent key, and `(int) null` is 0, which
     * would mean one attempt and no wait.
     */
    public function testAnAbsentRetryConfigFallsBackToThePackagedDefaults(): void
    {
        $this->app->get('config')->set('cin7.retry.times', null);
        $this->app->get('config')->set('cin7.retry.delay_ms', null);

        $mock = Saloon::fake(array_fill(0, 4, MockResponse::make(Cin7Payloads::throttled(), 503)));

        $request = new ListRecords(Endpoint::Customer);

        $this->expectException(ServerException::class);

        try {
            $this->connector()->send($request);
        } finally {
            $mock->assertSentCount(4);
            Sleep::assertSleptTimes(3);
        }
    }
}
