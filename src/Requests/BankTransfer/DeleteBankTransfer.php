<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\BankTransfer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\BankTransfer\BankTransferData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE bankTransfer?ID&Void`, voids or undoes a bank transfer; the response is the bank transfer.
 *
 * @extends Cin7Request<BankTransferData>
 */
final class DeleteBankTransfer extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = BankTransferData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
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
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }
}
