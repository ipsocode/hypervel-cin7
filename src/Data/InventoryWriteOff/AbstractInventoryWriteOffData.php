<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The fields of the Inventory Write-Off table: the response of every `inventoryWriteOff` action
 * and the body of its POST and PUT. Each is a final child that adds its own fields.
 *
 * Every write-off needs its `Status` and `Account`, so each child passes them to this constructor;
 * the optional fields declared here are set through `from()`. A write body also needs a location,
 * `LocationID` or `Location`, and an `EffectiveDate` when `Status` is `COMPLETED`, or it fails
 * validation before it is sent. The table types `Location` as a Decimal, copied from the field
 * above it: it is the location's name.
 *
 * @see docs/data.md
 */
abstract class AbstractInventoryWriteOffData extends Data
{
    #[RequiredWithout('Location')]
    #[Uuid]
    public ?string $LocationID = null;

    #[RequiredWithout('LocationID')]
    public ?string $Location = null;

    #[RequiredIf('Status', CompletionStatus::Completed)]
    #[DateTime]
    public ?string $EffectiveDate = null;

    public ?string $Notes = null;

    /**
     * @var null|list<InventoryWriteOffLineData>
     */
    #[DataCollectionOf(InventoryWriteOffLineData::class)]
    public ?array $Lines = null;

    public function __construct(
        public CompletionStatus $Status,
        public string $Account,
    ) {
    }
}
