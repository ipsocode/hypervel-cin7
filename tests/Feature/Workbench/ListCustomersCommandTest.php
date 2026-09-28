<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Workbench;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * `cin7:customers` exists so the package can be poked at by hand against the
 * live API.
 *
 * Testing it is worth the few cases below for two reasons. It is the only place
 * the console kernel resolves a Workbench service, so it proves
 * `workbench.discovers.commands` is actually wired; and a hand-run tool that is
 * broken is worse than none, because it gets reached for exactly when something
 * else is already wrong.
 */
class ListCustomersCommandTest extends TestCase
{
    public function testTheWorkbenchCommandIsDiscovered(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->artisan('cin7:customers')->assertExitCode(0);
    }

    public function testItPrintsTheCustomersItWasGiven(): void
    {
        Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([
                Cin7Payloads::customer('guid-1', 'ACME'),
                Cin7Payloads::customer('guid-2', 'Globex'),
            ])),
        ]);

        $this->artisan('cin7:customers')
            ->expectsOutputToContain('ACME')
            ->expectsOutputToContain('Globex')
            ->assertExitCode(0);
    }

    public function testItSaysSoWhenDearReturnsNothing(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->artisan('cin7:customers')
            ->expectsOutput('No customers returned.')
            ->assertExitCode(0);
    }

    public function testTheLimitAndNameOptionsReachTheQueryString(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->artisan('cin7:customers --limit=5 --name=ACME')->assertExitCode(0);

        $this->assertSame(
            ['limit' => 5, 'Name' => 'ACME', 'page' => 1],
            $mock->lastPendingRequest()->queryParameters(),
        );
    }

    /**
     * `AlwaysThrowOnErrors` turns a non-2xx into an exception, so a rejected
     * credential reaches the command as a throw rather than an empty list.
     * Running this against the live API with the suite's fake credentials is
     * the most likely first experience of the command, so it has to report the
     * rejection rather than print "No customers returned.".
     *
     * 403 rather than 401 is what Cin7 actually answers — observed by running
     * `vendor/bin/testbench cin7:customers` with the test credentials, which
     * comes back `403 Incorrect credentials!`.
     */
    public function testItReportsADearErrorRatherThanPrintingAnEmptyTable(): void
    {
        // One attempt: a 403 is not retried, but pinning the config keeps this
        // independent of the packaged default.
        $this->app->get('config')->set('cin7.retry.times', 1);

        Saloon::fake([MockResponse::make(Cin7Payloads::error('Incorrect credentials!'), 403)]);

        $this->artisan('cin7:customers')
            ->expectsOutputToContain('Cin7 responded 403')
            ->assertExitCode(1);
    }
}
