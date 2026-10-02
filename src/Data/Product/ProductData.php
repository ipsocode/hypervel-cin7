<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;
use Ipsocode\Cin7\Data\ProductPriceData;

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
    use HasResponse;

    /**
     * @param array<string, float>|Optional $PriceTiers
     * @param list<ProductSupplierData>|Optional $Suppliers
     * @param list<ReorderLevelData>|Optional $ReorderLevels
     * @param list<BillOfMaterialProductData>|Optional $BillOfMaterialsProducts
     * @param list<BillOfMaterialServiceData>|Optional $BillOfMaterialsServices
     * @param list<ProductMovementData>|Optional $Movements
     * @param list<AttachmentLineData>|Optional $Attachments
     * @param list<ProductPriceData>|Optional $CustomPrices
     */
    public function __construct(
        public string|Optional $ID,
        public string|Optional $SKU,
        public string|Optional $Name,
        public string|Optional $Category,
        public string|Optional|null $Brand,
        public string|Optional $Type,
        public string|Optional $CostingMethod,
        public string|Optional $DropShipMode,
        public string|Optional|null $DefaultLocation,
        public float|Optional $Length,
        public float|Optional $Width,
        public float|Optional $Height,
        public float|Optional $Weight,
        public float|Optional $CartonLength,
        public float|Optional $CartonWidth,
        public float|Optional $CartonHeight,
        public float|Optional $CartonQuantity,
        public float|Optional $CartonInnerQuantity,
        public string|Optional $UOM,
        public string|Optional|null $WeightUnits,
        public string|Optional|null $DimensionsUnits,
        public string|Optional|null $Barcode,
        public float|Optional $MinimumBeforeReorder,
        public float|Optional $ReorderQuantity,
        public float|Optional $PriceTier1,
        public float|Optional $PriceTier2,
        public float|Optional $PriceTier3,
        public float|Optional $PriceTier4,
        public float|Optional $PriceTier5,
        public float|Optional $PriceTier6,
        public float|Optional $PriceTier7,
        public float|Optional $PriceTier8,
        public float|Optional $PriceTier9,
        public float|Optional $PriceTier10,
        public array|Optional $PriceTiers,
        public float|Optional $AverageCost,
        public string|Optional|null $ShortDescription,
        public string|Optional|null $Description,
        public string|Optional|null $InternalNote,
        public string|Optional|null $AdditionalAttribute1,
        public string|Optional|null $AdditionalAttribute2,
        public string|Optional|null $AdditionalAttribute3,
        public string|Optional|null $AdditionalAttribute4,
        public string|Optional|null $AdditionalAttribute5,
        public string|Optional|null $AdditionalAttribute6,
        public string|Optional|null $AdditionalAttribute7,
        public string|Optional|null $AdditionalAttribute8,
        public string|Optional|null $AdditionalAttribute9,
        public string|Optional|null $AdditionalAttribute10,
        public string|Optional|null $AttributeSet,
        public string|Optional|null $DiscountRule,
        public string|Optional|null $Tags,
        public string|Optional $Status,
        public string|Optional|null $StockLocator,
        public string|Optional|null $COGSAccount,
        public string|Optional|null $RevenueAccount,
        public string|Optional|null $ExpenseAccount,
        public string|Optional|null $InventoryAccount,
        public string|Optional|null $PurchaseTaxRule,
        public string|Optional|null $SaleTaxRule,
        public string|Optional $LastModifiedOn,
        public bool|Optional $Sellable,
        public string|Optional|null $PickZones,
        public bool|Optional $BillOfMaterial,
        public bool|Optional $AutoAssembly,
        public bool|Optional $AutoDisassembly,
        public float|Optional $QuantityToProduce,
        public string|Optional|null $AssemblyInstructionURL,
        public string|Optional|null $AssemblyCostEstimationMethod,
        public string|Optional|null $BOMType,
        public string|Optional|null $HSCode,
        public string|Optional|null $CountryOfOrigin,
        public string|Optional|null $CountryOfOriginCode,
        #[DataCollectionOf(ProductSupplierData::class)]
        public array|Optional $Suppliers,
        #[DataCollectionOf(ReorderLevelData::class)]
        public array|Optional $ReorderLevels,
        #[DataCollectionOf(BillOfMaterialProductData::class)]
        public array|Optional $BillOfMaterialsProducts,
        #[DataCollectionOf(BillOfMaterialServiceData::class)]
        public array|Optional $BillOfMaterialsServices,
        #[DataCollectionOf(ProductMovementData::class)]
        public array|Optional $Movements,
        #[DataCollectionOf(AttachmentLineData::class)]
        public array|Optional $Attachments,
        #[DataCollectionOf(ProductPriceData::class)]
        public array|Optional $CustomPrices,
    ) {
    }
}
