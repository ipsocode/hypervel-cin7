<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Account\AccountData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/account`, body is an `AccountPostData`; the response is the list envelope holding the
 * saved account.
 *
 * @extends WriteRequest<AccountData>
 */
final class PostAccount extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'ref/account';
    }

    public function createDtoFromResponse(Response $response): AccountData
    {
        return AccountData::from($response->json('AccountsList.0'))->setResponse($response);
    }
}
