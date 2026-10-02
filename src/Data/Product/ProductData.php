<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Concerns\HasAdditionalAttributes;
use Ipsocode\Cin7\Data\ProductPriceData;
use Ipsocode\Cin7\Enums\CostingMethod;
use Ipsocode\Cin7\Enums\DropShipMode;
use Ipsocode\Cin7\Enums\ProductStatus;
use Ipsocode\Cin7\Enums\ProductType;

/**
 * Product, the body of `product` POST and PUT and the entries of `Products` in every `product` response.
 *
 * `PriceTiers` is a map of the account's price tier names to prices, e.g. `['Tier 1' => 8.0]`. The names can be
 * renamed in the account's settings, so they cannot be properties and there is no `PriceTierData`.
 * `AdditionalAttribute1` to `AdditionalAttribute10` are ten wire keys.
 *
 * @see docs/data.md
 */
final class ProductData extends Data implements WithResponse
{
    use HasAdditionalAttributes;
    use HasResponse;

    /**
     * @param null|array<string, float> $PriceTiers
     * @param null|list<ProductSupplierData> $Suppliers
     * @param null|list<ReorderLevelData> $ReorderLevels
     * @param null|list<BillOfMaterialProductData> $BillOfMaterialsProducts
     * @param null|list<BillOfMaterialServiceData> $BillOfMaterialsServices
     * @param null|list<ProductMovementData> $Movements
     * @param null|list<AttachmentLineData> $Attachments
     * @param null|list<ProductPriceData> $CustomPrices
     */
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(256)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $Category = null,
        #[Max(50)]
        public ?string $Brand = null,
        public ?ProductType $Type = null,
        public ?CostingMethod $CostingMethod = null,
        public ?DropShipMode $DropShipMode = null,
        #[Max(50)]
        public ?string $DefaultLocation = null,
        public ?float $Length = null,
        public ?float $Width = null,
        public ?float $Height = null,
        public ?float $Weight = null,
        public ?float $CartonLength = null,
        public ?float $CartonWidth = null,
        public ?float $CartonHeight = null,
        public ?float $CartonQuantity = null,
        public ?float $CartonInnerQuantity = null,
        #[Max(50)]
        public ?string $UOM = null,
        #[Max(10)]
        public ?string $WeightUnits = null,
        #[Max(10)]
        public ?string $DimensionsUnits = null,
        #[Max(256)]
        public ?string $Barcode = null,
        public ?float $MinimumBeforeReorder = null,
        public ?float $ReorderQuantity = null,
        public ?float $PriceTier1 = null,
        public ?float $PriceTier2 = null,
        public ?float $PriceTier3 = null,
        public ?float $PriceTier4 = null,
        public ?float $PriceTier5 = null,
        public ?float $PriceTier6 = null,
        public ?float $PriceTier7 = null,
        public ?float $PriceTier8 = null,
        public ?float $PriceTier9 = null,
        public ?float $PriceTier10 = null,
        public ?array $PriceTiers = null,
        public ?float $AverageCost = null,
        #[Max(500)]
        public ?string $ShortDescription = null,
        public ?string $Description = null,
        #[Max(4000)]
        public ?string $InternalNote = null,
        #[Max(50)]
        public ?string $AttributeSet = null,
        #[Max(128)]
        public ?string $DiscountRule = null,
        #[Max(256)]
        public ?string $Tags = null,
        public ?ProductStatus $Status = null,
        #[Max(256)]
        public ?string $StockLocator = null,
        #[Max(50)]
        public ?string $COGSAccount = null,
        #[Max(50)]
        public ?string $RevenueAccount = null,
        #[Max(50)]
        public ?string $ExpenseAccount = null,
        #[Max(50)]
        public ?string $InventoryAccount = null,
        #[Max(50)]
        public ?string $PurchaseTaxRule = null,
        #[Max(50)]
        public ?string $SaleTaxRule = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
        public ?bool $Sellable = null,
        #[Max(256)]
        public ?string $PickZones = null,
        public ?bool $BillOfMaterial = null,
        public ?bool $AutoAssembly = null,
        public ?bool $AutoDisassembly = null,
        public ?float $QuantityToProduce = null,
        #[Max(256)]
        public ?string $AssemblyInstructionURL = null,
        #[Max(256)]
        public ?string $AssemblyCostEstimationMethod = null,
        public ?string $BOMType = null,
        #[Max(200)]
        public ?string $HSCode = null,
        public ?string $CountryOfOrigin = null,
        public ?string $CountryOfOriginCode = null,
        #[DataCollectionOf(ProductSupplierData::class)]
        public ?array $Suppliers = null,
        #[DataCollectionOf(ReorderLevelData::class)]
        public ?array $ReorderLevels = null,
        #[DataCollectionOf(BillOfMaterialProductData::class)]
        public ?array $BillOfMaterialsProducts = null,
        #[DataCollectionOf(BillOfMaterialServiceData::class)]
        public ?array $BillOfMaterialsServices = null,
        #[DataCollectionOf(ProductMovementData::class)]
        public ?array $Movements = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(ProductPriceData::class)]
        public ?array $CustomPrices = null,
    ) {
    }
}
