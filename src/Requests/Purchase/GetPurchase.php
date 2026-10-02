<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\PurchaseData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase?ID`, one simple purchase, with its order, stock received, invoice, credit note and
 * manual journal. Optional parameter: `CombineAdditionalCharges`. `purchase` has no list action;
 * use `purchaseList` for that.
 *
 * @extends Cin7Request<PurchaseData>
 */
final class GetPurchase extends Cin7Request
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
        return 'purchase';
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

    public function createDtoFromResponse(Response $response): PurchaseData
    {
        return PurchaseData::from($response->json())->setResponse($response);
    }
}
