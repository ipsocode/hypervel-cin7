<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Enums\CountryFormat;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale?ID`, one sale. `sale` has no list action; use `saleList` for that.
 *
 * @extends Cin7Request<SaleData>
 */
final class GetSale extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $combineAdditionalCharges = null,
        protected readonly ?bool $hideInventoryMovements = null,
        protected readonly ?bool $includeTransactions = null,
        protected readonly ?CountryFormat $countryFormat = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
            'HideInventoryMovements' => $this->hideInventoryMovements,
            'IncludeTransactions' => $this->includeTransactions,
            'CountryFormat' => $this->countryFormat,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleData
    {
        return SaleData::from($response->json())->setResponse($response);
    }
}
