<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Carrier;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/carrier` — the list envelope is keyed `CarrierList`.
 *
 * @extends ListRequest<list<CarrierData>>
 */
final class GetCarrier extends ListRequest
{
    protected string $listKey = 'CarrierList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $carrierId = null,
        protected readonly ?string $description = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/carrier';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'CarrierID' => $this->carrierId,
            'Description' => $this->description,
        ];
    }

    /**
     * @return list<CarrierData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): CarrierData => CarrierData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
