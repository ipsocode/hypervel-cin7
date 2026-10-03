<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account\Bank;

use Ipsocode\Cin7\Data\Ref\Account\Bank\BankAccountData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/account/bank` — the list envelope is keyed `BankAccountsList`.
 *
 * @extends ListRequest<BankAccountData>
 */
final class GetAccountBank extends ListRequest
{
    protected string $listKey = 'BankAccountsList';

    protected string $item = BankAccountData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?string $bank = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/account/bank';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Bank' => $this->bank,
        ];
    }
}
