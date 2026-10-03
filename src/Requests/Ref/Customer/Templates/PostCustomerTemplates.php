<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Templates;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplateData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/customer/templates`, body is a `CustomerDefaultTemplatesPostData`; the response lists the customers' default templates.
 *
 * @extends WriteRequest<list<CustomerDefaultTemplateData>>
 */
final class PostCustomerTemplates extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'ref/customer/templates';
    }

    /**
     * @return list<CustomerDefaultTemplateData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(CustomerDefaultTemplateData::class, $response, $response->json('CustomerTemplates'));
    }
}
