<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\BankTransfer;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = BankTransferData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
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
}
