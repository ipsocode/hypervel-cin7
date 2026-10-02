<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\AttachmentLineData;
use Ipsocode\Cin7\Data\Concerns\HasAdditionalAttributes;
use Ipsocode\Cin7\Data\ProductPriceData;
use Ipsocode\Cin7\Enums\CostingMethod;
use Ipsocode\Cin7\Enums\DropShipMode;
use Ipsocode\Cin7\Enums\ProductStatus;

/**
 * The fields of the Product table: the response of `product` and the body of its POST and PUT.
 * Each is a final child that adds its own fields.
 *
 * Every product needs its `SKU`, `Name`, `Category`, `CostingMethod`, `UOM` and `Status`, so each
 * child passes them to this constructor; the optional fields declared here are set through
 * `from()`. A product with a bill of materials (`BillOfMaterial` true) also needs
 * `QuantityToProduce` and `AssemblyCostEstimationMethod`, which a write body is checked for.
 * `PriceTiers` is a map of the account's price tier names to prices, e.g. `['Tier 1' => 8.0]`:
 * the names can be renamed in the account's settings, so they cannot be properties and there is
 * no `PriceTierData`. `AdditionalAttribute1` to `AdditionalAttribute10` are ten wire keys.
 *
 * @see docs/data.md
 */
abstract class AbstractProductData extends Data
{
    use HasAdditionalAttributes;

    #[Max(50)]
    public ?string $Brand = null;

    public ?DropShipMode $DropShipMode = null;

    #[Max(50)]
    public ?string $DefaultLocation = null;

    public ?float $Length = null;

    public ?float $Width = null;

    public ?float $Height = null;

    public ?float $Weight = null;

    public ?float $CartonLength = null;

    public ?float $CartonWidth = null;

    public ?float $CartonHeight = null;

    public ?float $CartonQuantity = null;

    public ?float $CartonInnerQuantity = null;

    #[Max(10)]
    public ?string $WeightUnits = null;

    #[Max(10)]
    public ?string $DimensionsUnits = null;

    #[Max(256)]
    public ?string $Barcode = null;

    public ?float $MinimumBeforeReorder = null;

    public ?float $ReorderQuantity = null;

    public ?float $PriceTier1 = null;

    public ?float $PriceTier2 = null;

    public ?float $PriceTier3 = null;

    public ?float $PriceTier4 = null;

    public ?float $PriceTier5 = null;

    public ?float $PriceTier6 = null;

    public ?float $PriceTier7 = null;

    public ?float $PriceTier8 = null;

    public ?float $PriceTier9 = null;

    public ?float $PriceTier10 = null;

    /**
     * @var null|array<string, float>
     */
    public ?array $PriceTiers = null;

    #[Max(500)]
    public ?string $ShortDescription = null;

    public ?string $Description = null;

    #[Max(4000)]
    public ?string $InternalNote = null;

    #[Max(50)]
    public ?string $AttributeSet = null;

    #[Max(128)]
    public ?string $DiscountRule = null;

    #[Max(256)]
    public ?string $Tags = null;

    #[Max(256)]
    public ?string $StockLocator = null;

    #[Max(50)]
    public ?string $COGSAccount = null;

    #[Max(50)]
    public ?string $RevenueAccount = null;

    #[Max(50)]
    public ?string $ExpenseAccount = null;

    #[Max(50)]
    public ?string $InventoryAccount = null;

    #[Max(50)]
    public ?string $PurchaseTaxRule = null;

    #[Max(50)]
    public ?string $SaleTaxRule = null;

    public ?bool $Sellable = null;

    #[Max(256)]
    public ?string $PickZones = null;

    public ?bool $BillOfMaterial = null;

    public ?bool $AutoAssembly = null;

    public ?bool $AutoDisassembly = null;

    #[RequiredIf('BillOfMaterial', true)]
    public ?float $QuantityToProduce = null;

    #[Max(256)]
    public ?string $AssemblyInstructionURL = null;

    #[RequiredIf('BillOfMaterial', true)]
    #[Max(256)]
    public ?string $AssemblyCostEstimationMethod = null;

    #[Max(200)]
    public ?string $HSCode = null;

    public ?string $CountryOfOrigin = null;

    public ?string $CountryOfOriginCode = null;

    /**
     * @var null|list<ProductSupplierData>
     */
    #[DataCollectionOf(ProductSupplierData::class)]
    public ?array $Suppliers = null;

    /**
     * @var null|list<ReorderLevelData>
     */
    #[DataCollectionOf(ReorderLevelData::class)]
    public ?array $ReorderLevels = null;

    /**
     * @var null|list<BillOfMaterialProductData>
     */
    #[DataCollectionOf(BillOfMaterialProductData::class)]
    public ?array $BillOfMaterialsProducts = null;

    /**
     * @var null|list<BillOfMaterialServiceData>
     */
    #[DataCollectionOf(BillOfMaterialServiceData::class)]
    public ?array $BillOfMaterialsServices = null;

    /**
     * @var null|list<ProductMovementData>
     */
    #[DataCollectionOf(ProductMovementData::class)]
    public ?array $Movements = null;

    /**
     * @var null|list<AttachmentLineData>
     */
    #[DataCollectionOf(AttachmentLineData::class)]
    public ?array $Attachments = null;

    /**
     * @var null|list<ProductPriceData>
     */
    #[DataCollectionOf(ProductPriceData::class)]
    public ?array $CustomPrices = null;

    public function __construct(
        #[Max(50)]
        public string $SKU,
        #[Max(256)]
        public string $Name,
        #[Max(256)]
        public string $Category,
        public CostingMethod $CostingMethod,
        #[Max(50)]
        public string $UOM,
        public ProductStatus $Status,
    ) {
    }
}
