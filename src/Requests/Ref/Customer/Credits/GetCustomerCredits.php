<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Credits;

use Ipsocode\Cin7\Data\Ref\Customer\Credits\CustomerCreditData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/customer/credits` — the list envelope is keyed `CustomerCredits` and carries no
 * `Total`, so a page is the last one when it holds fewer items than the limit sent.
 *
 * @extends ListRequest<CustomerCreditData>
 */
final class GetCustomerCredits extends ListRequest
{
    protected string $listKey = 'CustomerCredits';

    protected string $item = CustomerCreditData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $customerId = null,
        protected readonly ?bool $showUsedCredits = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/customer/credits';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'CustomerID' => $this->customerId,
            'ShowUsedCredits' => $this->showUsedCredits,
        ];
    }
}
