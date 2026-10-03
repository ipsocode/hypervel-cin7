<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoicesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/invoice?PurchaseID`, an advanced purchase's invoices. Optional parameter:
 * `CombineAdditionalCharges`.
 *
 * @extends Cin7Request<AdvancedPurchaseInvoicesData>
 */
final class GetAdvancedPurchaseInvoice extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $purchaseId,
        protected readonly ?bool $combineAdditionalCharges = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/invoice';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'PurchaseID' => $this->purchaseId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseInvoicesData
    {
        return AdvancedPurchaseInvoicesData::from($response->json())->setResponse($response);
    }
}
