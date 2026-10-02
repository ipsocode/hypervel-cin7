<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferPostData;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferPutData;
use Ipsocode\Cin7\Requests\BankTransfer\DeleteBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\GetBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\PostBankTransfer;
use Ipsocode\Cin7\Requests\BankTransfer\PutBankTransfer;

/**
 * `bankTransfer`, a transfer between two bank accounts. It has no list action; V2 lists bank
 * transfers at `moneyTaskList`, which is `Cin7Connector::moneyTaskList()`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class BankTransferResource extends BaseResource
{
    /**
     * One bank transfer.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetBankTransfer($taskId));
    }

    /**
     * @param array<string, mixed>|BankTransferPostData $body
     */
    public function post(array|BankTransferPostData $body): Response
    {
        return $this->connector->send(new PostBankTransfer($body));
    }

    /**
     * @param array<string, mixed>|BankTransferPutData $body
     */
    public function put(array|BankTransferPutData $body): Response
    {
        return $this->connector->send(new PutBankTransfer($body));
    }

    /**
     * Void the bank transfer (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteBankTransfer($id, $void));
    }
}
