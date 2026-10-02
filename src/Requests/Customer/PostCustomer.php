<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST customer`, body is a `CustomerPostData`; the response is the saved Customer.
 * Cin7 marks `LastModifiedOn`, `ChildCustomers` (response only) and the price's `ProductName`
 * read-only, so they are left out of an array body.
 *
 * @extends WriteRequest<CustomerData>
 */
final class PostCustomer extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['LastModifiedOn', 'ChildCustomers', 'ProductPrices.*.ProductName'];

    public function resolveEndpoint(): string
    {
        return 'customer';
    }

    public function createDtoFromResponse(Response $response): CustomerData
    {
        return CustomerData::from($response->json('CustomerList.0'))->setResponse($response);
    }
}
