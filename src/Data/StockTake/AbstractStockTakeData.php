<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTake;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\ExistingStockLineData;
use Ipsocode\Cin7\Data\Other\NewStockLineData;

/**
 * The fields of the Stock Take table: the response of every `stocktake` action and the body of its
 * POST and PUT. Each is a final child that adds its own fields.
 *
 * Every stock take needs its `EffectiveDate` and `Account`, so each child passes them to this
 * constructor; the optional fields declared here are set through `from()`. It also needs a
 * location, `LocationID` or `Location`, so a write body without either fails validation before it
 * is sent. The filters (`Tags`, `PickZones`, `StockLocators`, `Categories`, `Brands`, `Bins`) pick
 * the products Cin7 puts in `NonZeroStockOnHandProducts`.
 *
 * @see docs/data.md
 */
abstract class AbstractStockTakeData extends Data
{
    #[RequiredWithout('Location')]
    public ?string $LocationID = null;

    #[RequiredWithout('LocationID')]
    public ?string $Location = null;

    /**
     * @var null|list<string>
     */
    public ?array $Tags = null;

    /**
     * @var null|list<string>
     */
    public ?array $PickZones = null;

    /**
     * @var null|list<string>
     */
    public ?array $StockLocators = null;

    /**
     * @var null|list<IdNameData>
     */
    #[DataCollectionOf(IdNameData::class)]
    public ?array $Categories = null;

    /**
     * @var null|list<IdNameData>
     */
    #[DataCollectionOf(IdNameData::class)]
    public ?array $Brands = null;

    /**
     * @var null|list<IdNameData>
     */
    #[DataCollectionOf(IdNameData::class)]
    public ?array $Bins = null;

    public ?string $Reference = null;

    /**
     * @var null|list<ExistingStockLineData>
     */
    #[DataCollectionOf(ExistingStockLineData::class)]
    public ?array $NonZeroStockOnHandProducts = null;

    /**
     * @var null|list<NewStockLineData>
     */
    #[DataCollectionOf(NewStockLineData::class)]
    public ?array $ZeroStockOnHandProducts = null;

    public ?bool $UseRelativeQuantity = null;

    public function __construct(
        #[DateTime]
        public string $EffectiveDate,
        public string $Account,
    ) {
    }
}
