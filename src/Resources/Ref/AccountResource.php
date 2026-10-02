<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Account\AccountPostData;
use Ipsocode\Cin7\Data\Ref\Account\AccountPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Account\DeleteAccount;
use Ipsocode\Cin7\Requests\Ref\Account\GetAccount;
use Ipsocode\Cin7\Requests\Ref\Account\PostAccount;
use Ipsocode\Cin7\Requests\Ref\Account\PutAccount;

/**
 * `ref/account`, the chart of accounts.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AccountResource extends BaseResource
{
    /**
     * One page of accounts; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $code only the account with this code
     * @param null|string $name only accounts whose name starts with this
     * @param null|string $type only accounts of this type
     * @param null|string $status only accounts with this status
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $code = null,
        ?string $name = null,
        ?string $type = null,
        ?string $status = null,
    ): Response {
        return $this->connector->send(new GetAccount($page, $limit, $code, $name, $type, $status));
    }

    /**
     * Every page of accounts, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $code only the account with this code
     * @param null|string $name only accounts whose name starts with this
     * @param null|string $type only accounts of this type
     * @param null|string $status only accounts with this status
     */
    public function paginate(
        ?int $limit = null,
        ?string $code = null,
        ?string $name = null,
        ?string $type = null,
        ?string $status = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetAccount(null, $limit, $code, $name, $type, $status));
    }

    /**
     * @param AccountPostData|array<string, mixed> $body
     */
    public function post(array|AccountPostData $body): Response
    {
        return $this->connector->send(new PostAccount($body));
    }

    /**
     * @param AccountPutData|array<string, mixed> $body
     */
    public function put(array|AccountPutData $body): Response
    {
        return $this->connector->send(new PutAccount($body));
    }

    /**
     * Delete the account with this code.
     */
    public function delete(string $code): Response
    {
        return $this->connector->send(new DeleteAccount($code));
    }
}
