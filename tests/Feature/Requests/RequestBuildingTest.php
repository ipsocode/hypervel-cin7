<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use DateTimeImmutable;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Pagination\Contracts\Paginatable;
use InvalidArgumentException;
use Ipsocode\Cin7\Data\Customer\CustomerPutData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxComponentData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Enums\CountryFormat;
use Ipsocode\Cin7\Enums\PickingStatus;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;
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
        $this->mock = Saloon::fake(array_fill(0, 7, MockResponse::make(Cin7Payloads::customerList())));
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

    public function testListSendsTheCallersPageLimitAndFilters(): void
    {
        $pending = $this->send(new GetCustomer(page: 4, limit: 10, name: 'ACME'));

        $this->assertSame(
            ['Name' => 'ACME', 'page' => 4, 'limit' => 10],
            $pending->queryParameters(),
        );
    }

    public function testANullParameterIsLeftOut(): void
    {
        $pending = $this->send(new GetCustomer(page: null, limit: null, name: null));

        $this->assertSame(['page' => 1, 'limit' => 100], $pending->queryParameters());
    }

    public function testBooleanFiltersAreSentAsTrueAndFalseStrings(): void
    {
        $pending = $this->send(new GetProduct(includeDeprecated: true, includeBom: false));

        $this->assertSame(
            ['IncludeDeprecated' => 'true', 'IncludeBOM' => 'false', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    /**
     * Cin7 reads dates as ISO 8601 in UTC with milliseconds, `yyyy-MM-ddTHH:mm:ss.fff`.
     */
    public function testDateFiltersAreSentInUtcWithMilliseconds(): void
    {
        $pending = $this->send(new GetCustomer(modifiedSince: new DateTimeImmutable('2012-11-14T23:28:33.363+10:00')));

        $this->assertSame(
            ['ModifiedSince' => '2012-11-14T13:28:33.363', 'page' => 1, 'limit' => 100],
            $pending->queryParameters(),
        );
    }

    public function testADateGivenAsAStringIsSentAsWritten(): void
    {
        $pending = $this->send(new GetSaleList(updatedSince: '2012-11-14'));

        $this->assertSame(['UpdatedSince' => '2012-11-14', 'page' => 1, 'limit' => 100], $pending->queryParameters());
    }

    public function testAnEnumParameterIsSentAsItsValue(): void
    {
        $this->assertSame(
            ['CombinedPickStatus' => 'NOT PICKED', 'Status' => 'ORDERED', 'page' => 1, 'limit' => 100],
            $this->send(new GetSaleList(combinedPickStatus: PickingStatus::NotPicked, status: SaleStatus::Ordered))->queryParameters(),
        );
        $this->assertSame(
            ['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CountryFormat' => 'Code2'],
            $this->send(new GetSale('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', countryFormat: CountryFormat::Code2))->queryParameters(),
        );
    }

    public function testOnlyAListRequestIsPaginatable(): void
    {
        $this->assertInstanceOf(Paginatable::class, new GetCustomer);
        $this->assertNotInstanceOf(Paginatable::class, new PostCustomer);
        $this->assertNotInstanceOf(Paginatable::class, new PutCustomer);
    }

    public function testPaginatingAGetSaleThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->connector()->paginate(new GetSale('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'));
    }

    public function testTheIdentifierComesFirstAndTheUnsetParametersAreLeftOut(): void
    {
        $pending = $this->send(new GetSale('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', includeTransactions: true));

        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'IncludeTransactions' => 'true'], $pending->queryParameters());
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
        $pending = $this->send(new PutCustomer(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf4', 'Name' => 'ACME Ltd']));

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf4', 'Name' => 'ACME Ltd'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    /**
     * `Optional` properties are left out of the body, so a PUT never sends a key the caller
     * did not set. A collection sent as `[]` would delete the records on the other side.
     */
    public function testADataObjectSendsOnlyTheKeysThatWereSet(): void
    {
        $pending = $this->send(new PutTax(TaxData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'])));

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Name' => 'VAT'], $pending->body());
        $this->assertArrayNotHasKey('Components', $pending->body());
    }

    /**
     * A data object's null means not set, so it is never sent; an explicit null, to clear a
     * field, goes in an array body, which is sent as given.
     */
    public function testANullIsSentOnlyFromAnArrayBody(): void
    {
        $customer = Cin7Payloads::customer('0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1');

        $this->assertArrayNotHasKey('TaxNumber', $this->send(new PutCustomer(CustomerPutData::from([...$customer, 'TaxNumber' => null])))->body());
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxNumber' => null], $this->send(new PutCustomer(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'TaxNumber' => null]))->body());
    }

    /**
     * Nulls go at every depth, but a list keeps its items so it still encodes as a JSON array.
     */
    public function testNestedNullsAreLeftOutAndListsKeepTheirShape(): void
    {
        $body = $this->send(new PutTax(TaxData::from([
            'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1',
            'Components' => [['Name' => 'GST', 'AccountCode' => null], ['Name' => 'PST']],
        ])))->body();

        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'Components' => [['Name' => 'GST'], ['Name' => 'PST']]], $body);
        $this->assertSame('{"ID":"0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1","Components":[{"Name":"GST"},{"Name":"PST"}]}', json_encode($body));
    }

    public function testASetCollectionIsSentAndAnUnsetOneIsLeftOut(): void
    {
        $withComponents = TaxData::from([
            'Name' => 'VAT',
            'Components' => [['Name' => 'Tax', 'Percent' => '20.0000000000', 'ComponentOrder' => '1']],
        ]);

        $this->assertInstanceOf(TaxComponentData::class, $withComponents->Components[0]);
        $this->assertSame(
            ['Name' => 'VAT', 'Components' => [['Name' => 'Tax', 'Percent' => '20.0000000000', 'ComponentOrder' => '1']]],
            $this->send(new PutTax($withComponents))->body(),
        );
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
}
