<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Pagination;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\Contracts\Paginatable;
use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
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

        $paginator = $this->connector()->paginate(new GetCustomer);

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
            $this->connector()->paginate(new GetCustomer)->items(),
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

        $paginator = $this->connector()->paginate(new GetCustomer)->perPageLimit(1);

        $items = iterator_to_array($paginator->items(), false);

        $this->assertSame(['ACME', 'Globex', 'Initech'], array_column($items, 'Name'));
        $mock->assertSentCount(3);
        $this->assertSame(3, $paginator->totalResults());
    }

    public function testItWalksTwoPagesOfAProductsEnvelope(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::products([['ID' => 'a', 'Name' => 'Widget']], page: 1, total: 2)),
            MockResponse::make(Cin7Payloads::products([['ID' => 'b', 'Name' => 'Gadget']], page: 2, total: 2)),
        ]);

        $paginator = $this->connector()->product()->paginate()->perPageLimit(1);

        $items = iterator_to_array($paginator->items(), false);

        $this->assertSame(['Widget', 'Gadget'], array_column($items, 'Name'));
        $mock->assertSentCount(2);
        $this->assertSame(2, $paginator->totalResults());
    }

    public function testEachPageRequestCarriesTheLowercasePageAndLimitParameters(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a')], page: 1, total: 2)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('b')], page: 2, total: 2)),
        ]);

        iterator_to_array(
            $this->connector()->paginate(new GetCustomer)->perPageLimit(1)->items(),
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

        $this->connector()->paginate(new GetCustomer)->current();

        $this->assertSame(['page' => 1, 'limit' => 100], $mock->lastPendingRequest()->queryParameters());
    }

    public function testAnEnvelopeWithNoListArrayYieldsNoItems(): void
    {
        Saloon::fake([MockResponse::make(['Total' => 0, 'Page' => 1])]);

        $items = iterator_to_array(
            $this->connector()->paginate(new GetCustomer)->items(),
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
            $this->connector()->paginate(new GetCustomer)->items(),
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
            $this->connector()->paginate(new GetCustomer(['limit' => 5]))->items(),
            false,
        );

        $this->assertSame(['ACME', 'Globex'], array_column($items, 'Name'));
        $mock->assertSentCount(2);
    }

    public function testAnEnvelopeWithNoTotalStopsOnAShortPage(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerCredits([['ID' => 'a'], ['ID' => 'b']], page: 1)),
            MockResponse::make(Cin7Payloads::customerCredits([['ID' => 'c']], page: 2)),
        ]);

        $paginator = $this->connector()->ref()->customer()->credits()->paginate()->perPageLimit(2);

        $items = iterator_to_array($paginator->items(), false);

        $this->assertSame(['a', 'b', 'c'], array_column($items, 'ID'));
        $mock->assertSentCount(2);
    }

    public function testAnEnvelopeWithNoTotalStopsOnAnEmptyPage(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerCredits([['ID' => 'a']], page: 1)),
            MockResponse::make(Cin7Payloads::customerCredits([], page: 2)),
        ]);

        $paginator = $this->connector()->ref()->customer()->credits()->paginate()->perPageLimit(1);

        $items = iterator_to_array($paginator->items(), false);

        $this->assertSame(['a'], array_column($items, 'ID'));
        $mock->assertSentCount(2);
    }

    public function testIteratingPagesYieldsResponsesWhoseDtoIsTyped(): void
    {
        Saloon::fake([
            MockResponse::make(Cin7Payloads::customerCredits([['CreditID' => 'a'], ['CreditID' => 'b']], page: 1)),
            MockResponse::make(Cin7Payloads::customerCredits([['CreditID' => 'c']], page: 2)),
        ]);

        $pages = [];

        foreach ($this->connector()->ref()->customer()->credits()->paginate()->perPageLimit(2) as $response) {
            $pages[] = array_map(static fn (CustomerCreditData $credit): string => $credit->CreditID, $response->dto());
        }

        $this->assertSame([['a', 'b'], ['c']], $pages);
    }

    public function testPooledFetchGathersEveryPageRegardlessOfCompletionOrder(): void
    {
        Saloon::fake([
            GetCustomer::class => function (PendingRequest $pendingRequest): MockResponse {
                $page = (int) $pendingRequest->queryParameters()['page'];

                return MockResponse::make(Cin7Payloads::customerList(
                    [Cin7Payloads::customer((string) $page, 'Customer ' . $page)],
                    page: $page,
                    total: 3,
                ));
            },
        ]);

        $paginator = $this->connector()->paginate(new GetCustomer)->perPageLimit(1);

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

    /**
     * Every shipped request implements `MapPaginatedResponseItems`, so the suffix lookup in
     * `getPageItems()` is only reachable through a request that does not.
     */
    public function testTheSuffixFallbackIsUsedWhenTheRequestDoesNotMapItsOwnItems(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a', 'ACME')]))]);

        $request = new class extends Cin7Request implements Paginatable {
            protected Method $method = Method::GET;

            public function resolveEndpoint(): string
            {
                return 'customer';
            }
        };

        $items = iterator_to_array($this->connector()->paginate($request)->items(), false);

        $this->assertSame(['ACME'], array_column($items, 'Name'));
    }

    /**
     * The suffix fallback's empty path, only reachable by a request that does not map its
     * own items against a body with no `…List` key.
     */
    public function testTheSuffixFallbackYieldsNoItemsWhenTheEnvelopeHasNoListKey(): void
    {
        Saloon::fake([MockResponse::make(['Total' => 0, 'Page' => 1])]);

        $request = new class extends Cin7Request implements Paginatable {
            protected Method $method = Method::GET;

            public function resolveEndpoint(): string
            {
                return 'customer';
            }
        };

        $items = iterator_to_array($this->connector()->paginate($request)->items(), false);

        $this->assertSame([], $items);
    }
}
