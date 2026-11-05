<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The runs of a production order, the answer of `production/order/run` GET and POST and of the run
 * actions: the `ProductionOrderID`, its `OrderNumber`, the `Runs` and any `Warnings`. The
 * reference documents no table for it: it is modeled from its examples.
 *
 * @see src/docs/data.md
 */
final class ProductionRunsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * `$Warnings` stays `list<mixed>`: the reference documents no model for it, and it is an empty
     * list in every `workbench/fixtures/production/order/run/` example that sends it.
     *
     * @param null|list<ProductionRunData> $Runs
     * @param null|list<mixed> $Warnings
     */
    public function __construct(
        #[Uuid]
        public ?string $ProductionOrderID = null,
        public ?string $OrderNumber = null,
        #[DataCollectionOf(ProductionRunData::class)]
        public ?array $Runs = null,
        public ?array $Warnings = null,
    ) {
    }
}
