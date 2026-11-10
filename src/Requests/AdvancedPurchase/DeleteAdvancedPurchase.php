<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE advanced-purchase?ID&Void`, voids or undoes a purchase; the response is the purchase.
 *
 * @extends Cin7Request<AdvancedPurchaseData>
 */
final class DeleteAdvancedPurchase extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = AdvancedPurchaseData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
    ) {
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
            'Void' => $this->void,
        ]);
    }
}
