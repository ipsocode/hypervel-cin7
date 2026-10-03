<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account;

use Ipsocode\Cin7\Data\Ref\Account\AccountData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/account` — the list envelope is keyed `AccountsList`.
 *
 * @extends ListRequest<AccountData>
 */
final class GetAccount extends ListRequest
{
    protected string $listKey = 'AccountsList';

    protected string $item = AccountData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $code = null,
        protected readonly ?string $name = null,
        protected readonly ?string $type = null,
        protected readonly ?string $status = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/account';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Code' => $this->code,
            'Name' => $this->name,
            'Type' => $this->type,
            'Status' => $this->status,
        ];
    }
}
