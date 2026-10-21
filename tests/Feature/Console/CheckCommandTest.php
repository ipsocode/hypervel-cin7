<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Console;

use Hypervel\Console\Application as ConsoleApplication;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Support\Facades\Artisan;
use Ipsocode\Cin7\Cin7ServiceProvider;
use Ipsocode\Cin7\Requests\Me\GetMe;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\TestCase;
use ReflectionProperty;
use RuntimeException;
use Workbench\App\Support\Cin7Payloads;

/**
 * `cin7:check`, one `GET me` that proves a connection's credentials and limiter store.
 *
 * @see docs/connector.md
 */
class CheckCommandTest extends TestCase
{
    public function testItPrintsTheCompanyCin7AnswersWith(): void
    {
        $mock = Saloon::fake([GetMe::class => Cin7Fake::record(Cin7Payloads::load('me', 'get.response'))]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('default')
            ->expectsOutputToContain('60 calls per 60s')
            ->expectsOutputToContain('worker-array')
            ->expectsOutputToContain('Example Company')
            ->expectsOutputToContain('RUB')
            ->expectsOutputToContain('Europe/Moscow')
            ->expectsOutputToContain('Cin7 accepted the credentials.')
            ->assertExitCode(0);

        $mock->assertSentCount(1);
    }

    public function testJsonPrintsOneObject(): void
    {
        Saloon::fake([GetMe::class => Cin7Fake::record(Cin7Payloads::load('me', 'get.response'))]);
        $this->app->get('config')->set('cin7.rate_limit.max', 0);

        $this->assertSame(0, Artisan::call('cin7:check', ['--json' => true]));
        $this->assertSame([
            'ok' => true,
            'connection' => 'default',
            'rate_limit' => 'off',
            'limiter_store' => 'worker-array',
            'company' => 'Example Company',
            'currency' => 'RUB',
            'time_zone' => 'Europe/Moscow',
        ], json_decode(Artisan::output(), true));
    }

    public function testItChecksTheConnectionItIsGiven(): void
    {
        $this->app->get('config')->set('cin7.connections.sandbox', [
            'account_id' => 'acct-sandbox',
            'application_key' => 'key-sandbox',
            'rate_limit' => ['max' => 30, 'period' => 60, 'store' => 'worker-array'],
        ]);
        $mock = Saloon::fake([GetMe::class => Cin7Fake::record(['Company' => 'Sandbox'])]);

        $this->artisan('cin7:check --connection=sandbox')
            ->expectsOutputToContain('sandbox')
            ->expectsOutputToContain('30 calls per 60s')
            ->doesntExpectOutputToContain('acct-sandbox')
            ->assertExitCode(0);

        $mock->assertSent(
            static fn (Request $request, Response $response): bool => $response->pendingRequest()->headers()['api-auth-accountid'] === 'acct-sandbox',
        );
    }

    public function testAnUnknownConnectionFails(): void
    {
        $mock = Saloon::fake([]);

        $this->artisan('cin7:check --connection=nope')
            ->expectsOutputToContain('Cin7 connection [nope] is not configured')
            ->assertExitCode(1);

        $mock->assertNothingSent();
    }

    public function testRejectedCredentialsFail(): void
    {
        Saloon::fake([GetMe::class => Cin7Fake::credentialsRejected()]);

        Artisan::call('cin7:check', ['--json' => true]);

        $this->assertSame([
            'ok' => false,
            'connection' => 'default',
            'rate_limit' => '60 calls per 60s',
            'limiter_store' => 'worker-array',
            'error' => 'Cin7 rejected the credentials (403).',
        ], json_decode(Artisan::output(), true));
    }

    public function testAnErrorModelBodyFailsWithItsText(): void
    {
        Saloon::fake([GetMe::class => Cin7Fake::error('Something went wrong', 400, 200)]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('Cin7 answered 200: Something went wrong')
            ->assertExitCode(1);
    }

    public function testAnotherFailureFailsWithItsStatus(): void
    {
        Saloon::fake([GetMe::class => MockResponse::make('Bad gateway', 502)]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('Cin7 answered 502.')
            ->assertExitCode(1);
    }

    public function testAnUnreachableCin7Fails(): void
    {
        Saloon::fake([GetMe::class => MockResponse::make()->throw(
            static fn (PendingRequest $request): FatalRequestException => new FatalRequestException(new RuntimeException('Connection refused'), $request),
        )]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('Could not reach Cin7: Connection refused')
            ->assertExitCode(1);
    }

    public function testAFailingLimiterStoreIsNamedAndNothingIsSent(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.store', 'missing');
        $mock = Saloon::fake([]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('The limiter store [missing] failed: Rate limiter store [missing] is not defined.')
            ->assertExitCode(1);

        $mock->assertNothingSent();
    }

    public function testABlankStoreFallsBackToTheSaloonStore(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.store', '');
        $this->app->get('config')->set('saloon.rate_limiter.store', 'missing');
        $mock = Saloon::fake([]);

        $this->artisan('cin7:check')
            ->expectsOutputToContain('The limiter store [missing] failed: Rate limiter store [missing] is not defined.')
            ->assertExitCode(1);

        $mock->assertNothingSent();
    }

    public function testNothingItPrintsCarriesTheCredentials(): void
    {
        $responses = [
            Cin7Fake::record(Cin7Payloads::load('me', 'get.response')),
            Cin7Fake::credentialsRejected(),
            Cin7Fake::error('Something went wrong'),
        ];

        foreach ($responses as $response) {
            Saloon::clearFake();
            Saloon::fake([GetMe::class => $response]);

            foreach ([[], ['--json' => true]] as $options) {
                Artisan::call('cin7:check', $options);
                $output = Artisan::output();

                $this->assertStringNotContainsString('acct-test', $output);
                $this->assertStringNotContainsString('key-test', $output);
            }
        }
    }

    public function testTheProviderRegistersItInTheConsole(): void
    {
        $this->assertArrayHasKey('cin7:check', Artisan::all());
    }

    public function testTheProviderRegistersNoCommandOutsideTheConsole(): void
    {
        $bootstrappers = new ReflectionProperty(ConsoleApplication::class, 'bootstrappers');
        $before = $bootstrappers->getValue();

        $this->app->setRunningInConsole(false);
        new Cin7ServiceProvider($this->app)->boot();

        $this->assertSame($before, $bootstrappers->getValue());
    }
}
