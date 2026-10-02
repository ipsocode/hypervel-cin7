<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/order?SaleID`, one sale's order. Optional parameters: `CombineAdditionalCharges` and `IncludeProductInfo`.
 *
 * @extends Cin7Request<SaleOrderData>
 */
final class GetSaleOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
        protected readonly ?bool $combineAdditionalCharges = null,
        protected readonly ?bool $includeProductInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/order';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'SaleID' => $this->saleId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
            'IncludeProductInfo' => $this->includeProductInfo,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleOrderData
    {
        return SaleOrderData::from($response->json())->setResponse($response);
    }
}
