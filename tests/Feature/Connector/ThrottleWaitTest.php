<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Connector;

use Hypervel\Support\Carbon;
use Hypervel\Support\Facades\Http;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * The two waits after a throttled answer, together: the request's retry delay and the cooldown
 * the connector records. They overlap, so one throttled attempt costs the longer of the two, not
 * their sum. A sleep moves the frozen clock the limiter reads, so the elapsed time is the total
 * wait.
 *
 * @see docs/connector.md
 */
class ThrottleWaitTest extends TestCase
{
    protected function tearDown(): void
    {
        Sleep::fake(false);
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function testA429WithRetryAfterWaitsTheLongerOfTheDelayAndRetryAfter(): void
    {
        $elapsed = $this->elapsedAfterOneThrottle(429, ['Retry-After' => '30']);

        $this->assertSame(30.0, $elapsed);
    }

    public function testA429WithAShortRetryAfterWaitsTheRetryDelay(): void
    {
        $elapsed = $this->elapsedAfterOneThrottle(429, ['Retry-After' => '2']);

        $this->assertSame(5.0, $elapsed);
    }

    public function testA503WaitsTheLongerOfTheDelayAndTheConfiguredCooldown(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.cooldown', 20);

        $this->assertSame(20.0, $this->elapsedAfterOneThrottle(503));
    }

    public function testA503WithAShortCooldownWaitsTheRetryDelay(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.cooldown', 2);

        $this->assertSame(5.0, $this->elapsedAfterOneThrottle(503));
    }

    /**
     * Seconds from the throttled answer to the successful retry.
     *
     * @param array<string, string> $headers
     */
    private function elapsedAfterOneThrottle(int $status, array $headers = []): float
    {
        Carbon::setTestNow('2026-10-09 12:00:00');
        Sleep::fake(syncWithCarbon: true);
        Http::fake(['*' => Http::sequence()
            ->push(['ErrorCode' => $status, 'Exception' => 'throttled'], $status, $headers)
            ->push(['Total' => 0, 'Page' => 1, 'CustomerList' => []])]);

        // The window is left open, so only the retry delay and the cooldown wait.
        $connector = new Cin7Connector('acct-throttle', 'key', 0, 0, null, (int) config('cin7.rate_limit.cooldown', 5));
        $request = new GetCustomer;

        $connector->send($request);

        Http::assertSentCount(2);

        return Carbon::now()->diffInSeconds('2026-10-09 12:00:00', true);
    }
}
