<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Templates;

use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplateData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/customer/templates` — the list envelope is keyed `CustomerTemplates` and carries no `Total`.
 *
 * @extends ListRequest<CustomerDefaultTemplateData>
 */
final class GetCustomerTemplates extends ListRequest
{
    protected string $listKey = 'CustomerTemplates';

    protected string $item = CustomerDefaultTemplateData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $customerId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/customer/templates';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'CustomerId' => $this->customerId,
        ];
    }
}
