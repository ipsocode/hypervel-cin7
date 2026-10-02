<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Pagination\Contracts\Paginatable;
use InvalidArgumentException;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Requests\KeyedRequest;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * Asserts on the PendingRequest the connector built, not on the request's own accessors.
 *
 * @see docs/requests.md
 */
class RequestBuildingTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        // One response per send in the longest test; only the request is asserted on.
        $this->mock = Saloon::fake(array_fill(0, 5, MockResponse::make(Cin7Payloads::customerList())));
    }

    public function testEveryRequestCarriesTheAuthAndContentTypeHeaders(): void
    {
        $pending = $this->send(new GetCustomer);

        $this->assertSame('acct-test', $pending->headers()['api-auth-accountid']);
        $this->assertSame('key-test', $pending->headers()['api-auth-applicationkey']);
        $this->assertSame('application/json', $pending->headers()['Content-Type']);
    }

    public function testTheBaseUrlAndEndpointPathAreJoined(): void
    {
        $pending = $this->send(new GetCustomer);

        $this->assertSame(
            'https://inventory.dearsystems.com/ExternalApi/v2/customer',
            $pending->uri()->getScheme() . '://' . $pending->uri()->getHost() . $pending->uri()->getPath(),
        );
    }

    public function testListInjectsThePageDefaults(): void
    {
        $pending = $this->send(new GetCustomer);

        $this->assertSame(Method::GET, $pending->method());
        $this->assertSame(['page' => 1, 'limit' => 100], $pending->queryParameters());
    }

    public function testListLetsCallerValuesWin(): void
    {
        $pending = $this->send(new GetCustomer(['page' => 4, 'limit' => 10, 'Name' => 'ACME']));

        $this->assertSame(
            ['page' => 4, 'limit' => 10, 'Name' => 'ACME'],
            $pending->queryParameters(),
        );
    }

    public function testANullFilterIsTreatedAsAbsent(): void
    {
        $pending = $this->send(new GetCustomer(['page' => null, 'limit' => null]));

        $this->assertSame(['page' => 1, 'limit' => 100], $pending->queryParameters());
    }

    public function testBooleanFiltersAreSentAsTrueAndFalseStrings(): void
    {
        $pending = $this->send(new GetCustomer(['IncludeDeprecated' => true, 'IncludeBOM' => false]));

        $this->assertSame(
            ['IncludeDeprecated' => 'true', 'IncludeBOM' => 'false', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    public function testOnlyAListRequestIsPaginatable(): void
    {
        $this->assertInstanceOf(Paginatable::class, new GetCustomer);
        $this->assertNotInstanceOf(Paginatable::class, new PostCustomer);
        $this->assertNotInstanceOf(Paginatable::class, new PutCustomer);
    }

    public function testPaginatingAKeyedRequestThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->connector()->paginate($this->anonymousKeyedRequest('guid-1'));
    }

    public function testTheIdentifierIsPlacedFirstAndWinsOverACallerSuppliedValue(): void
    {
        $pending = $this->send($this->anonymousKeyedRequest('guid-1', ['ID' => 'ignored', 'Extra' => 'kept']));

        $this->assertSame(['ID' => 'guid-1', 'Extra' => 'kept'], $pending->queryParameters());
    }

    public function testCreateSendsAnUntouchedJsonBodyAndNoQueryString(): void
    {
        $pending = $this->send(new PostCustomer(['Name' => 'ACME']));

        $this->assertSame(Method::POST, $pending->method());
        $this->assertSame(['Name' => 'ACME'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    /**
     * An empty create still sends a JSON body, `[]`, not a bodyless POST.
     */
    public function testCreateSendsAnEmptyBodyWhenGivenNoData(): void
    {
        $pending = $this->send(new PostCustomer);

        $this->assertSame([], $pending->body());
    }

    public function testUpdateSendsTheBodyVerbatim(): void
    {
        $pending = $this->send(new PutCustomer(['ID' => 'guid-4', 'Name' => 'ACME Ltd']));

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(['ID' => 'guid-4', 'Name' => 'ACME Ltd'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    public function testTheDecodedBodyIsReturnedAsAnArray(): void
    {
        $body = Cin7Payloads::customerList([Cin7Payloads::customer('a')]);

        Saloon::clearFake();
        Saloon::fake([MockResponse::make($body)]);

        $response = $this->connector()->send(new GetCustomer);

        $this->assertSame($body, $response->json());
        $this->assertSame('a', $response->json('CustomerList')[0]['ID']);
    }

    /**
     * Sends through the container's connector and returns the PendingRequest the mock recorded.
     */
    private function send(Request $request): PendingRequest
    {
        $this->connector()->send($request);

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);

        return $pending;
    }

    /**
     * No concrete `KeyedRequest` ships yet, so the GET/DELETE contract is exercised through
     * an anonymous one built on the `customer` path.
     *
     * @param array<string, mixed> $parameters
     */
    private function anonymousKeyedRequest(string $id, array $parameters = []): KeyedRequest
    {
        return new class($id, $parameters) extends KeyedRequest {
            protected Method $method = Method::GET;

            public function resolveEndpoint(): string
            {
                return 'customer';
            }
        };
    }
}
