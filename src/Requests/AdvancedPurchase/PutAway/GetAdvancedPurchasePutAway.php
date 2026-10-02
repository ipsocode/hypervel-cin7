<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwaysData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/put-away?PurchaseID`, an advanced purchase's put away tasks.
 *
 * @extends Cin7Request<AdvancedPurchasePutAwaysData>
 */
final class GetAdvancedPurchasePutAway extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $purchaseId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/put-away';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'PurchaseID' => $this->purchaseId,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchasePutAwaysData
    {
        return AdvancedPurchasePutAwaysData::from($response->json())->setResponse($response);
    }
}
