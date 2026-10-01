<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Pagination;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * `Cin7Paginator` against Cin7's `{Total, Page, <Thing>List}` envelope, as
 * `Cin7Payloads::customerList()` models it.
 *
 * @see docs/pagination.md
 */
class Cin7PaginatorTest extends TestCase
{
    public function testTheConnectorPaginatesWithACin7Paginator(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $paginator = $this->connector()->paginate(new ListRecords(Endpoint::Customer));

        $this->assertInstanceOf(Cin7Paginator::class, $paginator);
    }

    public function testASinglePageStopsAfterOneSend(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([
                Cin7Payloads::customer('a', 'ACME'),
                Cin7Payloads::customer('b', 'Globex'),
            ])),
        ]);

        $items = iterator_to_array(
            $this->connector()->paginate(new ListRecords(Endpoint::Customer))->items(),
            false,
        );

        $this->assertSame(['ACME', 'Globex'], array_column($items, 'Name'));
        $mock->assertSentCount(1);
    }

    public function testItWalksEveryPageInSequenceUntilTheTotalIsExhausted(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a', 'ACME')], page: 1, total: 3)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('b', 'Globex')], page: 2, total: 3)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('c', 'Initech')], page: 3, total: 3)),
        ]);

        $paginator = $this->connector()->paginate(new ListRecords(Endpoint::Customer))->perPageLimit(1);

        $items = iterator_to_array($paginator->items(), false);

        $this->assertSame(['ACME', 'Globex', 'Initech'], array_column($items, 'Name'));
        $mock->assertSentCount(3);
        $this->assertSame(3, $paginator->totalResults());
    }

    public function testEachPageRequestCarriesTheLowercasePageAndLimitParameters(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a')], page: 1, total: 2)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('b')], page: 2, total: 2)),
        ]);

        iterator_to_array(
            $this->connector()->paginate(new ListRecords(Endpoint::Customer))->perPageLimit(1)->items(),
            false,
        );

        $recorded = $mock->recorded()
            ->map(fn (Response $response): array => $response->pendingRequest()->queryParameters())
            ->all();

        $this->assertSame(
            [['page' => 1, 'limit' => 1], ['page' => 2, 'limit' => 1]],
            array_values($recorded),
        );
    }

    public function testWithNoExplicitLimitThePageDefaultsAreLeftAlone(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->connector()->paginate(new ListRecords(Endpoint::Customer))->current();

        $this->assertSame(['page' => 1, 'limit' => 100], $mock->lastPendingRequest()->queryParameters());
    }

    public function testAnEnvelopeWithNoListArrayYieldsNoItems(): void
    {
        Saloon::fake([MockResponse::make(['Total' => 0, 'Page' => 1])]);

        $items = iterator_to_array(
            $this->connector()->paginate(new ListRecords(Endpoint::Customer))->items(),
            false,
        );

        $this->assertSame([], $items);
    }

    /**
     * Only a key ending in `List` is the item list, even when another array comes first.
     */
    public function testAnUnrelatedArrayFieldDoesNotShadowTheListKey(): void
    {
        Saloon::fake([MockResponse::make([
            'Total' => 1,
            'Page' => 1,
            'Warnings' => ['Some non-fatal warning'],
            'CustomerList' => [Cin7Payloads::customer('a', 'ACME')],
        ])]);

        $items = iterator_to_array(
            $this->connector()->paginate(new ListRecords(Endpoint::Customer))->items(),
            false,
        );

        $this->assertSame(['ACME'], array_column($items, 'Name'));
    }

    /**
     * A Total of 7 at the sent limit of 5 is two pages; dividing by the default 100 would
     * stop after one.
     */
    public function testTotalPagesRespectsACallerSuppliedLimitEvenWithoutPerPageLimit(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a', 'ACME')], page: 1, total: 7)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('b', 'Globex')], page: 2, total: 7)),
        ]);

        $items = iterator_to_array(
            $this->connector()->paginate(new ListRecords(Endpoint::Customer, ['limit' => 5]))->items(),
            false,
        );

        $this->assertSame(['ACME', 'Globex'], array_column($items, 'Name'));
        $mock->assertSentCount(2);
    }

    public function testPooledFetchGathersEveryPageRegardlessOfCompletionOrder(): void
    {
        Saloon::fake([
            ListRecords::class => function (PendingRequest $pendingRequest): MockResponse {
                $page = (int) $pendingRequest->queryParameters()['page'];

                return MockResponse::make(Cin7Payloads::customerList(
                    [Cin7Payloads::customer((string) $page, 'Customer ' . $page)],
                    page: $page,
                    total: 3,
                ));
            },
        ]);

        $paginator = $this->connector()->paginate(new ListRecords(Endpoint::Customer))->perPageLimit(1);

        $responses = $paginator->pool(concurrency: 2);

        $this->assertSame([0, 1, 2], array_keys($responses));
        $this->assertSame(
            ['Customer 1', 'Customer 2', 'Customer 3'],
            array_map(
                fn (Response $response): mixed => $response->json('CustomerList')[0]['Name'],
                array_values($responses),
            ),
        );
    }
}
