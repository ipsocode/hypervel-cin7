<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/tax` — the list envelope is keyed `TaxRuleList`.
 *
 * @extends ListRequest<list<TaxData>>
 */
final class GetTax extends ListRequest
{
    protected string $listKey = 'TaxRuleList';

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }

    /**
     * @return list<TaxData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TaxData => TaxData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
