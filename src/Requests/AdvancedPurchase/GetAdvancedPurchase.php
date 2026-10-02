<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase?ID`, one purchase, simple, advanced or service, with its order and its
 * stock received, put away, invoices, credit notes and manual journals. Optional parameter:
 * `CombineAdditionalCharges`. `advanced-purchase` has no list action; use `purchaseList` for that.
 *
 * @extends Cin7Request<AdvancedPurchaseData>
 */
final class GetAdvancedPurchase extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $combineAdditionalCharges = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseData
    {
        return AdvancedPurchaseData::from($response->json())->setResponse($response);
    }
}
