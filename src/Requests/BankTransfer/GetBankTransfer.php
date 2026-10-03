<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\BankTransfer;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET bankTransfer?TaskID`, one bank transfer. V2 marks `TaskID` optional, but the transfers are
 * listed at `moneyTaskList`, so it is required here.
 *
 * @extends Cin7Request<BankTransferData>
 */
final class GetBankTransfer extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'bankTransfer';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): BankTransferData
    {
        return BankTransferData::from($response->json())->setResponse($response);
    }
}
