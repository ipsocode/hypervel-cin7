<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/invoice?SaleID`, a sale's invoices. Optional parameters: `CombineAdditionalCharges` and `IncludeProductInfo`.
 *
 * @extends Cin7Request<SaleInvoicesData>
 */
final class GetSaleInvoice extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = SaleInvoicesData::class;

    public function __construct(
        protected readonly string $saleId,
        protected readonly ?bool $combineAdditionalCharges = null,
        protected readonly ?bool $includeProductInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
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
}
