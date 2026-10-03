<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/tax` — the list envelope is keyed `TaxRuleList`.
 *
 * @extends ListRequest<TaxData>
 */
final class GetTax extends ListRequest
{
    protected string $listKey = 'TaxRuleList';

    protected string $item = TaxData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?bool $isActive = null,
        protected readonly ?bool $isTaxForSale = null,
        protected readonly ?bool $isTaxForPurchase = null,
        protected readonly ?string $account = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'IsActive' => $this->isActive,
            'IsTaxForSale' => $this->isTaxForSale,
            'IsTaxForPurchase' => $this->isTaxForPurchase,
            'Account' => $this->account,
        ];
    }
}
