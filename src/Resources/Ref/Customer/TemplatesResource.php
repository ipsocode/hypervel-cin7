<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref\Customer;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Customer\Templates\CustomerDefaultTemplatesPostData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\DeleteCustomerTemplates;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\GetCustomerTemplates;
use Ipsocode\Cin7\Requests\Ref\Customer\Templates\PostCustomerTemplates;

/**
 * `ref/customer/templates`, the customer default templates.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class TemplatesResource extends BaseResource
{
    /**
     * One page of customer default templates; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $customerId only this customer's default templates
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $customerId = null,
    ): Response {
        return $this->connector->send(new GetCustomerTemplates(
            $page,
            $limit,
            $customerId,
        ));
    }

    /**
     * Every page of customer default templates, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $customerId only this customer's default templates
     */
    public function paginate(
        ?int $limit = null,
        ?string $customerId = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCustomerTemplates(
            null,
            $limit,
            $customerId,
        ));
    }

    /**
     * @param array<string, mixed>|CustomerDefaultTemplatesPostData $body
     */
    public function post(array|CustomerDefaultTemplatesPostData $body): Response
    {
        return $this->connector->send(new PostCustomerTemplates($body));
    }

    /**
     * Delete a customer default template.
     *
     * @param string $templateId the ID of the template
     * @param string $customerId the ID of the customer
     */
    public function delete(string $templateId, string $customerId): Response
    {
        return $this->connector->send(new DeleteCustomerTemplates($templateId, $customerId));
    }
}
