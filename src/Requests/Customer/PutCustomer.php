<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT customer`, body is a Customer, and carries `ID`; the response is the saved Customer.
 *
 * @extends WriteRequest<CustomerData>
 */
final class PutCustomer extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'customer';
    }

    public function createDtoFromResponse(Response $response): CustomerData
    {
        return CustomerData::from($response->json('CustomerList.0'))->setResponse($response);
    }
}
