<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Templates;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplateData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE ref/customer/templates`, removes a customer's default template; the response lists the remaining ones.
 *
 * @extends Cin7Request<list<CustomerDefaultTemplateData>>
 */
final class DeleteCustomerTemplates extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $templateId,
        protected readonly string $customerId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'ref/customer/templates';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TemplateId' => $this->templateId,
            'CustomerId' => $this->customerId,
        ]);
    }

    /**
     * @return list<CustomerDefaultTemplateData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(CustomerDefaultTemplateData::class, $response, $response->json('CustomerTemplates'));
    }
}
