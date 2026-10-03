<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionOrderOperation, an operation of a production order: its attachments, components,
 * notes, resources, links and products. `OperationID` is required when updating; the work center's
 * co-man procurement type and `ComponentLocationID` are read-only.
 *
 * @see docs/data.md
 */
final class ProductionOrderOperationData extends Data
{
    /**
     * @param null|list<ProductionOrderOperationAttachmentData> $Attachments
     * @param null|list<ProductionOrderComponentData> $Components
     * @param null|list<ProductionOrderOperationNoteData> $Notes
     * @param null|list<ProductionOrderResourceData> $Resources
     * @param null|list<ProductionOrderOperationLinkData> $OperationLinks
     * @param null|list<ProductionOrderOperationProductData> $InputProducts
     * @param null|list<ProductionOrderOperationProductData> $OutputProducts
     * @param null|list<ProductionOrderOperationProductData> $FinishedProducts
     */
    public function __construct(
        public int $Order,
        #[Max(200)]
        public string $Name,
        public int $CycleTime,
        public float $UnitsPerCycle,
        public int $TotalCycleTime,
        #[Uuid]
        public ?string $OperationID = null,
        public ?float $TotalUnitsPerCycle = null,
        public ?string $OperationType = null,
        #[Uuid]
        public ?string $WorkCenterID = null,
        public ?string $WorkCenterName = null,
        public ?string $WorkCenterCode = null,
        public ?string $WorkCenterCoManProcurementType = null,
        #[Uuid]
        public ?string $ComponentLocationID = null,
        public ?bool $IsDropShip = null,
        #[DataCollectionOf(ProductionOrderOperationAttachmentData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(ProductionOrderComponentData::class)]
        public ?array $Components = null,
        #[DataCollectionOf(ProductionOrderOperationNoteData::class)]
        public ?array $Notes = null,
        #[DataCollectionOf(ProductionOrderResourceData::class)]
        public ?array $Resources = null,
        #[DataCollectionOf(ProductionOrderOperationLinkData::class)]
        public ?array $OperationLinks = null,
        #[DataCollectionOf(ProductionOrderOperationProductData::class)]
        public ?array $InputProducts = null,
        #[DataCollectionOf(ProductionOrderOperationProductData::class)]
        public ?array $OutputProducts = null,
        #[DataCollectionOf(ProductionOrderOperationProductData::class)]
        public ?array $FinishedProducts = null,
        public ?bool $IsBackflush = null,
    ) {
    }
}
