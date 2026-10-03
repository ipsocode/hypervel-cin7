<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields of a product family's production BOM that the family's response and its PUT body
 * share, optional in each. Both require an `OutputQuantity`, which the constructor takes; the
 * response adds the `BufferPercent` its table requires and the PUT the `BOMID`.
 *
 * @see docs/data.md
 */
abstract class AbstractProductFamilyProductionBomData extends Data
{
    public ?string $InstructionUrl = null;

    public ?bool $IgnoreCumulativeLeadTime = null;

    public ?int $ComponentProductionLeadTime = null;

    #[Uuid]
    public ?string $DeliveryToID = null;

    #[Max(256)]
    public ?string $DeliveryToName = null;

    public string|int|null $IssueMethodComponent = null;

    public string|int|null $IssueMethodParameter = null;

    public ?float $MinQuantity = null;

    public ?float $MaxQuantity = null;

    public ?float $DeviationPercent = null;

    public ?float $RunSize = null;

    /**
     * @var null|list<ProductFamilyProductionBomOperationData>
     */
    #[DataCollectionOf(ProductFamilyProductionBomOperationData::class)]
    public ?array $Operations = null;

    public function __construct(
        public float $OutputQuantity,
    ) {
    }
}
