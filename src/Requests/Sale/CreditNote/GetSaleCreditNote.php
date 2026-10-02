<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/creditnote?SaleID`, a sale's credit notes. Optional parameters: `CombineAdditionalCharges`, `IncludeProductInfo` and `IncludePaymentInfo`.
 *
 * @extends Cin7Request<SaleCreditNotesData>
 */
final class GetSaleCreditNote extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
        protected readonly ?bool $combineAdditionalCharges = null,
        protected readonly ?bool $includeProductInfo = null,
        protected readonly ?bool $includePaymentInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/creditnote';
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
            'IncludePaymentInfo' => $this->includePaymentInfo,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleCreditNotesData
    {
        return SaleCreditNotesData::from($response->json())->setResponse($response);
    }
}
