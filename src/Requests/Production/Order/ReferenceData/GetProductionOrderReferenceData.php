<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\ReferenceData;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderReferenceData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/order/referenceData`, the reference data a production order uses.
 *
 * @extends Cin7Request<ProductionOrderReferenceData>
 */
final class GetProductionOrderReferenceData extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = ProductionOrderReferenceData::class;

    public function __construct(
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/order/referenceData';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([]);
    }
}
