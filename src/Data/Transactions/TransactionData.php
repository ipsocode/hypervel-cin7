<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Transactions;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\TransactionType;

/**
 * Transactions, one entry of `Transactions`: a movement between a debit and a credit account.
 *
 * @see docs/data.md
 */
final class TransactionData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $DebitAccountCode = null,
        public ?string $CreditAccountCode = null,
        public ?float $Amount = null,
        #[DateTime]
        public ?string $EffectiveDate = null,
        public ?string $Reference = null,
        public ?string $Transaction = null,
        public ?TransactionType $Type = null,
    ) {
    }
}
