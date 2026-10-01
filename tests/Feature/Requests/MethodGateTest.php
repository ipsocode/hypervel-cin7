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
 * The verb gate runs in the request constructor, so every test asserts nothing was sent,
 * not only that it threw.
 *
 * @see docs/requests.md
 */
class MethodGateTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        // Empty on purpose: any send exhausts it and fails the test.
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
     * Every endpoint/verb pair `writeMethods()` forbids.
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

    public function testTheRejectionNamesTheVerbAndTheEndpoint(): void
    {
        $this->expectException(MethodNotAllowedException::class);
        $this->expectExceptionMessage('Method [DELETE] is not allowed on the [customer] endpoint.');

        new DeleteRecord(Endpoint::Customer, 'guid-7');
    }

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
     * GET is granted by the base request, so it is allowed even where `writeMethods()` is empty.
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
