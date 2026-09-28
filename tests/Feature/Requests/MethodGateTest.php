<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Exceptions\MethodNotAllowedException;
use Ipsocode\Cin7\Requests\CreateRecord;
use Ipsocode\Cin7\Requests\DeleteRecord;
use Ipsocode\Cin7\Requests\FindRecord;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Requests\UpdateRecord;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The verb gate replaces upstream's marker interfaces.
 *
 * It runs in the request constructor, which is the whole point: an unsupported
 * verb is rejected before a PendingRequest exists, so Cin7 is never asked a
 * question it would answer with a 403. Every test here asserts nothing was
 * sent, not merely that something threw.
 */
class MethodGateTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        // Deliberately empty: any send at all would exhaust it and fail, which
        // is a stronger statement than assertNothingSent() alone.
        $this->mock = Saloon::fake();
    }

    #[DataProvider('forbiddenProvider')]
    public function testTheGateRejectsEveryVerbTheEndpointDoesNotDeclare(Endpoint $endpoint, Method $method): void
    {
        $this->expectException(MethodNotAllowedException::class);

        try {
            match ($method) {
                Method::POST => new CreateRecord($endpoint, ['Name' => 'ACME']),
                Method::PUT => new UpdateRecord($endpoint, 'guid', ['Name' => 'ACME']),
                default => new DeleteRecord($endpoint, 'guid'),
            };
        } finally {
            $this->mock->assertNothingSent();
        }
    }

    /**
     * Every endpoint/verb pair the table forbids, so a widened `writeMethods()`
     * fails here rather than at a consumer's first 403.
     *
     * @return array<string, array{Endpoint, Method}>
     */
    public static function forbiddenProvider(): array
    {
        $cases = [];

        foreach (Endpoint::cases() as $endpoint) {
            $allowed = $endpoint->writeMethods();

            foreach ([Method::POST, Method::PUT, Method::DELETE] as $method) {
                if (! in_array($method, $allowed, true)) {
                    $cases["{$endpoint->value} rejects {$method->value}"] = [$endpoint, $method];
                }
            }
        }

        return $cases;
    }

    /**
     * The message names both the verb and the endpoint accessor, because that
     * is what a caller passed in and what they have to change.
     */
    public function testTheRejectionNamesTheVerbAndTheEndpoint(): void
    {
        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionMessage('Method [DELETE] is not allowed on the [customer] endpoint.');

        new DeleteRecord(Endpoint::Customer, 'guid-7');
    }

    /**
     * The counterpart to the provider above: every verb an endpoint *does*
     * declare must be constructible, so the gate cannot be tightened by
     * accident either.
     */
    public function testTheGateAcceptsEveryVerbTheEndpointDeclares(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            foreach ($endpoint->writeMethods() as $method) {
                $request = match ($method) {
                    Method::POST => new CreateRecord($endpoint, ['Name' => 'ACME']),
                    Method::PUT => new UpdateRecord($endpoint, 'guid', ['Name' => 'ACME']),
                    default => new DeleteRecord($endpoint, 'guid'),
                };

                $this->assertSame($method, $request->method(), "{$endpoint->value} / {$method->value}");
            }
        }

        $this->mock->assertNothingSent();
    }

    /**
     * GET is granted by the base request rather than listed per endpoint, so it
     * is allowed everywhere — including on the two endpoints that declare no
     * write methods at all.
     */
    public function testReadsAreAllowedOnEveryEndpoint(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame(Method::GET, new ListRecords($endpoint)->method());
            $this->assertSame(Method::GET, new FindRecord($endpoint, 'guid')->method());
        }

        $this->mock->assertNothingSent();
    }
}
