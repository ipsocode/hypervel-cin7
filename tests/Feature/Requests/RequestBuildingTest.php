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
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\CreateRecord;
use Ipsocode\Cin7\Requests\DeleteRecord;
use Ipsocode\Cin7\Requests\FindRecord;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Requests\UpdateRecord;
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
        $pending = $this->send(new ListRecords(Endpoint::Customer));

        $this->assertSame('acct-test', $pending->headers()['api-auth-accountid']);
        $this->assertSame('key-test', $pending->headers()['api-auth-applicationkey']);
        $this->assertSame('application/json', $pending->headers()['Content-Type']);
    }

    public function testTheBaseUrlAndEndpointPathAreJoined(): void
    {
        $pending = $this->send(new ListRecords(Endpoint::SaleInvoice));

        $this->assertSame(
            'https://inventory.dearsystems.com/ExternalApi/v2/sale/invoice',
            $pending->uri()->getScheme() . '://' . $pending->uri()->getHost() . $pending->uri()->getPath(),
        );
    }

    public function testEveryEndpointResolvesToItsOwnPath(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame($endpoint->path(), new ListRecords($endpoint)->resolveEndpoint());
        }
    }

    public function testARequestExposesTheEndpointItTargets(): void
    {
        $this->assertSame(Endpoint::SaleInvoice, new ListRecords(Endpoint::SaleInvoice)->endpoint());
        $this->assertSame(Endpoint::Customer, new FindRecord(Endpoint::Customer, 'guid')->endpoint());
        $this->assertSame(Endpoint::Product, new CreateRecord(Endpoint::Product, [])->endpoint());
        $this->assertSame(Endpoint::Tax, new UpdateRecord(Endpoint::Tax, 'guid', [])->endpoint());
        $this->assertSame(Endpoint::Sale, new DeleteRecord(Endpoint::Sale, 'guid')->endpoint());
    }

    public function testListInjectsThePageDefaults(): void
    {
        $pending = $this->send(new ListRecords(Endpoint::Customer));

        $this->assertSame(Method::GET, $pending->method());
        $this->assertSame(['page' => 1, 'limit' => 100], $pending->queryParameters());
    }

    public function testListLetsCallerValuesWin(): void
    {
        $pending = $this->send(new ListRecords(Endpoint::Customer, ['page' => 4, 'limit' => 10, 'Name' => 'ACME']));

        $this->assertSame(
            ['page' => 4, 'limit' => 10, 'Name' => 'ACME'],
            $pending->queryParameters(),
        );
    }

    public function testOnlyTheListRequestIsPaginatable(): void
    {
        $this->assertInstanceOf(Paginatable::class, new ListRecords(Endpoint::Customer));
        $this->assertNotInstanceOf(Paginatable::class, new FindRecord(Endpoint::Customer, 'guid'));
        $this->assertNotInstanceOf(Paginatable::class, new CreateRecord(Endpoint::Customer, []));
    }

    public function testFindSendsTheGuidAsAQueryParameter(): void
    {
        $pending = $this->send(new FindRecord(Endpoint::Customer, 'guid-1'));

        $this->assertSame(Method::GET, $pending->method());
        $this->assertSame(
            ['ID' => 'guid-1', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    public function testFindUsesTheSaleIdKeyOnSaleSubEndpoints(): void
    {
        $pending = $this->send(new FindRecord(Endpoint::SaleInvoice, 'guid-2'));

        $this->assertSame(
            ['SaleID' => 'guid-2', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
        $this->assertArrayNotHasKey('ID', $pending->queryParameters());
    }

    public function testFindGuidWinsOverACallerSuppliedValue(): void
    {
        $pending = $this->send(new FindRecord(Endpoint::Customer, 'guid-3', ['ID' => 'ignored']));

        $this->assertSame('guid-3', $pending->queryParameters()['ID']);
    }

    public function testCreateSendsAnUntouchedJsonBodyAndNoPageDefaults(): void
    {
        $pending = $this->send(new CreateRecord(Endpoint::Customer, ['Name' => 'ACME']));

        $this->assertSame(Method::POST, $pending->method());
        $this->assertSame(['Name' => 'ACME'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    /**
     * An empty create still sends a JSON body, `[]`, not a bodyless POST.
     */
    public function testCreateSendsAnEmptyBodyWhenGivenNoData(): void
    {
        $pending = $this->send(new CreateRecord(Endpoint::Customer));

        $this->assertSame([], $pending->body());
    }

    public function testUpdateMergesTheGuidIntoTheBody(): void
    {
        $pending = $this->send(new UpdateRecord(Endpoint::Customer, 'guid-4', ['Name' => 'ACME Ltd']));

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(
            ['Name' => 'ACME Ltd', 'ID' => 'guid-4'],
            $pending->body(),
        );
        $this->assertSame([], $pending->queryParameters());
    }

    public function testUpdateGuidWinsOverACallerSuppliedValue(): void
    {
        $pending = $this->send(new UpdateRecord(Endpoint::Customer, 'guid-8', ['ID' => 'ignored']));

        $this->assertSame(['ID' => 'guid-8'], $pending->body());
    }

    /**
     * An update carries the GUID under `guidKey()`, the find key; `sale` finds by `ID`, and no
     * endpoint that finds by `SaleID` accepts a PUT.
     */
    public function testUpdateUsesTheFindKeyOnSaleSubEndpoints(): void
    {
        $pending = $this->send(new UpdateRecord(Endpoint::Sale, 'guid-9', ['Status' => 'AUTHORISED']));

        $this->assertSame(['Status' => 'AUTHORISED', 'ID' => 'guid-9'], $pending->body());
    }

    public function testDeleteUsesTheDeleteGuidKeyAndKeepsThePageDefaults(): void
    {
        // `sale/invoice` finds by SaleID, but Cin7 reads a delete's GUID under ID.
        $pending = $this->send(new DeleteRecord(Endpoint::SaleInvoice, 'guid-5'));

        $this->assertSame(Method::DELETE, $pending->method());
        $this->assertSame(
            ['ID' => 'guid-5', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    public function testDeleteGuidWinsOverACallerSuppliedValue(): void
    {
        $pending = $this->send(new DeleteRecord(Endpoint::Sale, 'guid-10', ['ID' => 'ignored', 'Force' => 'true']));

        $this->assertSame(
            ['ID' => 'guid-10', 'Force' => 'true', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    public function testTheDecodedBodyIsReturnedAsAnArray(): void
    {
        $body = Cin7Payloads::customerList([Cin7Payloads::customer('a')]);

        Saloon::clearFake();
        Saloon::fake([MockResponse::make($body)]);

        $response = $this->connector()->send(new ListRecords(Endpoint::Customer));

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
}
