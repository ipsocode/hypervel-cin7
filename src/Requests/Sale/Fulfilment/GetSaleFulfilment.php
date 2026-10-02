<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/fulfilment?SaleID`, a sale's fulfilments. Optional parameter: `IncludeProductInfo`.
 *
 * @extends Cin7Request<SaleFulfilmentsData>
 */
final class GetSaleFulfilment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
        protected readonly ?bool $includeProductInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'SaleID' => $this->saleId,
            'IncludeProductInfo' => $this->includeProductInfo,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentsData
    {
        return SaleFulfilmentsData::from($response->json())->setResponse($response);
    }
}
