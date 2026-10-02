<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/order?TaskID`, a purchase's order. Optional parameter: `CombineAdditionalCharges`.
 *
 * @extends Cin7Request<PurchaseOrderData>
 */
final class GetPurchaseOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $combineAdditionalCharges = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/order';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseOrderData
    {
        return PurchaseOrderData::from($response->json())->setResponse($response);
    }
}
