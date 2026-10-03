<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMOperation of a product family's production BOM: the product BOM operation, whose
 * `VariationComponents` the family's examples fill. The `OperationID` is required when updating;
 * `WorkCenterCoManProcurementType` and `IssueMethod` are read-only, and `IssueMethod` is a code
 * (`1`, `2`, `3`, `4`) in the examples. `OperationType` is `Manufacturing`, `Setup`, `QA` or
 * `CoManufacturing`, but the POST examples send `1`, so it stays a string or an int.
 * `CycleTimeString`, `IsTracing` and `TotalCost` are in the examples, not the table, and so is the
 * product BOM's empty `VariationComponents`.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomOperationData extends Data
{
    /**
     * @param null|list<ProductionBomResourceData> $Resources
     * @param null|list<ProductionBomComponentData> $Components
     * @param null|list<ProductionBomAttachmentData> $Attachments
     * @param null|list<ProductionBomNoteData> $Notes
     * @param null|list<ProductionBomOperationLinkData> $OperationLinks
     * @param null|list<ProductionBomOperationProductData> $InputProducts
     * @param null|list<ProductionBomOperationProductData> $OutputProducts
     * @param null|list<ProductionBomOperationProductData> $FinishedProducts
     * @param null|list<ProductionBomVariationComponentData> $VariationComponents
     */
    public function __construct(
        public int $Order,
        #[Max(200)]
        public string $Name,
        public int $CycleTime,
        public float $UnitsPerCycle,
        #[Uuid]
        public string $WorkCenterID,
        public string|int $OperationType,
        public bool $IsDropShip,
        #[Uuid]
        public ?string $OperationID = null,
        #[Max(256)]
        public ?string $WorkCenterName = null,
        public ?string $WorkCenterCoManProcurementType = null,
        #[Uuid]
        public ?string $DeliveryToID = null,
        #[Max(256)]
        public ?string $DeliveryToName = null,
        public string|int|null $IssueMethod = null,
        #[DataCollectionOf(ProductionBomResourceData::class)]
        public ?array $Resources = null,
        #[DataCollectionOf(ProductionBomComponentData::class)]
        public ?array $Components = null,
        #[DataCollectionOf(ProductionBomAttachmentData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(ProductionBomNoteData::class)]
        public ?array $Notes = null,
        #[DataCollectionOf(ProductionBomOperationLinkData::class)]
        public ?array $OperationLinks = null,
        #[DataCollectionOf(ProductionBomOperationProductData::class)]
        public ?array $InputProducts = null,
        #[DataCollectionOf(ProductionBomOperationProductData::class)]
        public ?array $OutputProducts = null,
        #[DataCollectionOf(ProductionBomOperationProductData::class)]
        public ?array $FinishedProducts = null,
        public ?bool $IsBackflush = null,
        public ?bool $IsTracing = null,
        public ?string $CycleTimeString = null,
        public ?float $TotalCost = null,
        #[DataCollectionOf(ProductionBomVariationComponentData::class)]
        public ?array $VariationComponents = null,
    ) {
    }
}
