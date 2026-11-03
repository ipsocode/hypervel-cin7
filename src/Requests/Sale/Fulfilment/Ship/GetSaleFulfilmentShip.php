<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/fulfilment/ship?TaskID`, a fulfilment's shipment.
 *
 * @extends Cin7Request<SaleFulfilmentShipData>
 */
final class GetSaleFulfilmentShip extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = SaleFulfilmentShipData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/ship';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }
}
