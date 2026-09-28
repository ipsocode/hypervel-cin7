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
 * Wire-protocol parity with `eighteen73/dear-api`.
 *
 * Everything here asserts on the PendingRequest the connector actually built —
 * the headers, URL, query and body as they would have gone out — rather than on
 * the request object's own accessors, so a change in how Saloon assembles a
 * request cannot pass unnoticed.
 */
class RequestBuildingTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        // One canned response per send in the longest test below; the body is
        // irrelevant to these assertions, which read the *request*.
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

    /**
     * `resolveEndpoint()` is what the URL above is built from; asserting it
     * directly pins the request-side contract for every endpoint at once,
     * without sending eleven requests to do it.
     */
    public function testEveryEndpointResolvesToItsOwnPath(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame($endpoint->path(), new ListRecords($endpoint)->resolveEndpoint());
        }
    }

    /**
     * The endpoint a request was built for is public, so a caller holding the
     * request — a pool, a retry wrapper, a log line — can name it without
     * re-parsing the URL.
     */
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

    /**
     * `ListRecords` is the only request Saloon may paginate: a find returns one
     * record and the writes are not pages. Dropping the interface would leave
     * `$connector->paginate()` refusing the one request it should accept.
     */
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
     * A create with no attributes is still a well-formed request — upstream
     * sent `{}` rather than omitting the body — so the empty default must not
     * collapse into a bodyless POST.
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
     * An update on a `sale/*` endpoint carries `SaleID` — the find key, not the
     * delete key — because Cin7 reads the body of a PUT the way it reads the
     * query of a GET.
     */
    public function testUpdateUsesTheFindKeyOnSaleSubEndpoints(): void
    {
        $pending = $this->send(new UpdateRecord(Endpoint::Sale, 'guid-9', ['Status' => 'AUTHORISED']));

        $this->assertSame(['Status' => 'AUTHORISED', 'ID' => 'guid-9'], $pending->body());
    }

    public function testDeleteUsesTheDeleteGuidKeyAndKeepsThePageDefaults(): void
    {
        // `sale/invoice` finds by SaleID but deletes by ID — upstream behavior,
        // encoded explicitly rather than inherited by accident.
        $pending = $this->send(new DeleteRecord(Endpoint::SaleInvoice, 'guid-5'));

        $this->assertSame(Method::DELETE, $pending->method());
        $this->assertSame(
            ['ID' => 'guid-5', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    /**
     * A delete takes caller parameters too, and the GUID is assigned last so it
     * wins — the same precedence a find has.
     */
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
     * Send the request through the container's connector and hand back the
     * PendingRequest the mock recorded.
     */
    private function send(Request $request): PendingRequest
    {
        $this->connector()->send($request);

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);

        return $pending;
    }
}
