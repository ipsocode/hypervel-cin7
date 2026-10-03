<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\BankTransfer;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT bankTransfer`, body is a `BankTransferPutData` and carries the `TaskID`; the response is the saved
 * bank transfer.
 *
 * @extends WriteRequest<BankTransferData>
 */
final class PutBankTransfer extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'bankTransfer';
    }

    public function createDtoFromResponse(Response $response): BankTransferData
    {
        return BankTransferData::from($response->json())->setResponse($response);
    }
}
