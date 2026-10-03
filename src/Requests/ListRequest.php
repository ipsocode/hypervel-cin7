<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\Contracts\MapPaginatedResponseItems;
use Hypervel\Saloon\Pagination\Contracts\Paginatable;
use Ipsocode\Cin7\PageDefaults;

/**
 * Lists an endpoint's records.
 *
 * @see docs/requests.md
 *
 * @template TItem of Data&WithResponse
 * @extends Cin7Request<list<TItem>>
 */
abstract class ListRequest extends Cin7Request implements MapPaginatedResponseItems, Paginatable
{
    protected Method $method = Method::GET;

    /**
     * The envelope key the list items sit under, e.g. `CustomerList`.
     */
    protected string $listKey;

    /**
     * The data class of one list item.
     *
     * @var class-string<TItem>
     */
    protected string $item;

    /**
     * Without a page or limit the request asks for page 1 of 100; the paginator sets both for
     * each page it fetches.
     */
    public function __construct(
        protected readonly ?int $page = null,
        protected readonly ?int $limit = null,
    ) {
        parent::__construct();
    }

    /**
     * The endpoint's documented filters by wire key, a `null` one left out.
     *
     * @return array<string, mixed>
     */
    abstract protected function filters(): array;

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return PageDefaults::apply($this->queryValues(
            $this->filters() + ['page' => $this->page, 'limit' => $this->limit],
        ));
    }

    /**
     * @return list<TItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf($this->item, $response, $this->mapPaginatedResponseItems($response));
    }

    /**
     * @param Response<mixed> $response
     * @return array<array-key, mixed>
     */
    public function mapPaginatedResponseItems(Response $response): array
    {
        return (array) $response->json($this->listKey, []);
    }
}
