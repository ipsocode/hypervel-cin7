<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

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
 * @template TDto
 * @extends Cin7Request<TDto>
 */
abstract class ListRequest extends Cin7Request implements MapPaginatedResponseItems, Paginatable
{
    protected Method $method = Method::GET;

    /**
     * The envelope key the list items sit under, e.g. `CustomerList`.
     */
    protected string $listKey;

    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(protected readonly array $filters = [])
    {
        parent::__construct();
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return PageDefaults::apply($this->queryValues($this->filters));
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
