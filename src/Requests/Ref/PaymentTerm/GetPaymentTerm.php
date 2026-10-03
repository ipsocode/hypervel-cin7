<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\PaymentTerm;

use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermData;
use Ipsocode\Cin7\Enums\PaymentTermMethod;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/paymentterm` — the list envelope is keyed `PaymentTermList`.
 *
 * @extends ListRequest<PaymentTermData>
 */
final class GetPaymentTerm extends ListRequest
{
    protected string $listKey = 'PaymentTermList';

    protected string $item = PaymentTermData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?PaymentTermMethod $termMethod = null,
        protected readonly ?bool $isActive = null,
        protected readonly ?bool $isDefault = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/paymentterm';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Method' => $this->termMethod,
            'IsActive' => $this->isActive,
            'IsDefault' => $this->isDefault,
        ];
    }
}
