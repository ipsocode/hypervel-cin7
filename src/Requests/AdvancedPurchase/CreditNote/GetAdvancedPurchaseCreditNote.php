<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNotesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/creditnote?PurchaseID`, an advanced purchase's credit notes. Optional
 * parameter: `CombineAdditionalCharges`.
 *
 * @extends Cin7Request<AdvancedPurchaseCreditNotesData>
 */
final class GetAdvancedPurchaseCreditNote extends Cin7Request
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
        return 'advanced-purchase/creditnote';
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

    public function createDtoFromResponse(Response $response): AdvancedPurchaseCreditNotesData
    {
        return AdvancedPurchaseCreditNotesData::from($response->json())->setResponse($response);
    }
}
