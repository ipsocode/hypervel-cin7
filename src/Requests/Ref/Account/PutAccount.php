<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Account\AccountData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/account`, body is an `AccountPutData`, whose `Code` names the account to change;
 * the response is the list envelope holding the saved account. Cin7 refuses it while the Xero or
 * QuickBooks integration is on.
 *
 * @extends WriteRequest<AccountData>
 */
final class PutAccount extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/account';
    }

    public function createDtoFromResponse(Response $response): AccountData
    {
        return AccountData::from($response->json('AccountsList.0'))->setResponse($response);
    }
}
