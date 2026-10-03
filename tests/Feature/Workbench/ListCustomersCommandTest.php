<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Workbench;

use Hypervel\Saloon\Facades\Saloon;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * `cin7:customers` is the only place the console kernel resolves a Workbench service, so
 * these tests prove testbench.yaml's `workbench.discovers.commands` is wired.
 *
 * @see docs/testing.md
 */
class ListCustomersCommandTest extends TestCase
{
    public function testTheWorkbenchCommandIsDiscovered(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->artisan('cin7:customers')->assertExitCode(0);
    }

    public function testItPrintsTheCustomersItWasGiven(): void
    {
        Saloon::fake([
            Cin7Fake::list('CustomerList', [
                Cin7Payloads::customer('guid-1', 'ACME'),
                Cin7Payloads::customer('guid-2', 'Globex'),
            ]),
        ]);

        $this->artisan('cin7:customers')
            ->expectsOutputToContain('ACME')
            ->expectsOutputToContain('Globex')
            ->assertExitCode(0);
    }

    public function testItSaysSoWhenCin7ReturnsNothing(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->artisan('cin7:customers')
            ->expectsOutput('No customers returned.')
            ->assertExitCode(0);
    }

    public function testTheLimitAndNameOptionsReachTheQueryString(): void
    {
        $mock = Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->artisan('cin7:customers --limit=5 --name=ACME')->assertExitCode(0);

        $this->assertSame(
            ['Name' => 'ACME', 'limit' => 5, 'page' => 1],
            $mock->lastPendingRequest()->queryParameters(),
        );
    }

    /**
     * Cin7 answers bad credentials with `403 Incorrect credentials!`, which `AlwaysThrowOnErrors`
     * turns into an exception the command reports.
     */
    public function testItReportsACin7ErrorRatherThanPrintingAnEmptyTable(): void
    {
        // A 403 is not retried; one attempt keeps this off the packaged default.
        $this->app->get('config')->set('cin7.retry.times', 1);

        Saloon::fake([Cin7Fake::credentialsRejected()]);

        $this->artisan('cin7:customers')
            ->expectsOutputToContain('Cin7 responded 403')
            ->assertExitCode(1);
    }
}
