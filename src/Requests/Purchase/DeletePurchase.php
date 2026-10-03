<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\PurchaseData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE purchase?ID&Void`, voids or undoes a purchase; the response is the purchase.
 *
 * @extends Cin7Request<PurchaseData>
 */
final class DeletePurchase extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
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
            'Void' => $this->void,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseData
    {
        return PurchaseData::from($response->json())->setResponse($response);
    }
}
