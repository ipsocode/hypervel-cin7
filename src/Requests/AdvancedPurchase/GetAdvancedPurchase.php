<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = AdvancedPurchaseData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $combineAdditionalCharges = null,
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
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }
}
