<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Supplier\Deposits;

use Ipsocode\Cin7\Data\Ref\Supplier\Deposits\SupplierDepositData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/supplier/deposits` — the list envelope is keyed `SupplierDeposits` and carries no
 * `Total`, so a page is the last one when it holds fewer items than the limit sent.
 *
 * @extends ListRequest<SupplierDepositData>
 */
final class GetSupplierDeposits extends ListRequest
{
    protected string $listKey = 'SupplierDeposits';

    protected string $item = SupplierDepositData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $supplierId = null,
        protected readonly ?bool $showUsedDeposits = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/supplier/deposits';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'SupplierID' => $this->supplierId,
            'ShowUsedDeposits' => $this->showUsedDeposits,
        ];
    }
}
