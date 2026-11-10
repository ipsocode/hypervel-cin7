<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = AccountData::class;

    protected ?string $dtoPath = 'AccountsList.0';

    public function resolveEndpoint(): string
    {
        return 'ref/account';
    }
}
