<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Templates;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplateData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/customer/templates` — the list envelope is keyed `CustomerTemplates` and carries no `Total`.
 *
 * @extends ListRequest<list<CustomerDefaultTemplateData>>
 */
final class GetCustomerTemplates extends ListRequest
{
    protected string $listKey = 'CustomerTemplates';

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

    /**
     * @return list<CustomerDefaultTemplateData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): CustomerDefaultTemplateData => CustomerDefaultTemplateData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
