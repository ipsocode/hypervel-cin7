<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRun, a run of a production order: its operations, planned and finished products,
 * manual journals and traceability. `Number`, `Status` and the dates are read-only. `Status` is a
 * string: the examples send `OPERATIONS COMPLETED`, outside the listed values.
 *
 * @see docs/data.md
 */
final class ProductionRunData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductionRunOperationData> $Operations
     * @param null|list<ProductionRunOutputData> $Output
     * @param null|list<ProductionRunPendingOutputData> $PendingOutput
     * @param null|list<ProductionRunManualJournalData> $ManualJournals
     * @param null|list<ProductionRunTraceabilityData> $Traceability
     */
    public function __construct(
        #[Uuid]
        public ?string $RunID = null,
        public ?int $Number = null,
        public ?string $Status = null,
        public ?float $Quantity = null,
        #[Max(50)]
        public ?string $WIPAccount = null,
        #[DateTime]
        public ?string $StartDate = null,
        #[DateTime]
        public ?string $EndDate = null,
        #[DateTime]
        public ?string $DueDate = null,
        #[DateTime]
        public ?string $ReceivedDate = null,
        #[DateTime]
        public ?string $ScheduleStart = null,
        #[DateTime]
        public ?string $ScheduleDue = null,
        #[DataCollectionOf(ProductionRunOperationData::class)]
        public ?array $Operations = null,
        #[DataCollectionOf(ProductionRunOutputData::class)]
        public ?array $Output = null,
        #[DataCollectionOf(ProductionRunPendingOutputData::class)]
        public ?array $PendingOutput = null,
        #[DataCollectionOf(ProductionRunManualJournalData::class)]
        public ?array $ManualJournals = null,
        #[DataCollectionOf(ProductionRunTraceabilityData::class)]
        public ?array $Traceability = null,
        public string|int|null $IssueMethodParameter = null,
        public ?string $CustomField1 = null,
        public ?string $CustomField2 = null,
        public ?string $CustomField3 = null,
        public ?string $CustomField4 = null,
        public ?string $CustomField5 = null,
        public ?string $CustomField6 = null,
        public ?string $CustomField7 = null,
        public ?string $CustomField8 = null,
        public ?string $CustomField9 = null,
        public ?string $CustomField10 = null,
    ) {
    }
}
