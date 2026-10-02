<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
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
        public ?string $ID = null,
        public ?string $SKU = null,
        public ?string $Name = null,
        public ?string $Category = null,
        public ?string $Brand = null,
        public ?string $Type = null,
        public ?string $CostingMethod = null,
        public ?string $DropShipMode = null,
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
        public ?string $UOM = null,
        public ?string $WeightUnits = null,
        public ?string $DimensionsUnits = null,
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
        public ?string $ShortDescription = null,
        public ?string $Description = null,
        public ?string $InternalNote = null,
        public ?string $AdditionalAttribute1 = null,
        public ?string $AdditionalAttribute2 = null,
        public ?string $AdditionalAttribute3 = null,
        public ?string $AdditionalAttribute4 = null,
        public ?string $AdditionalAttribute5 = null,
        public ?string $AdditionalAttribute6 = null,
        public ?string $AdditionalAttribute7 = null,
        public ?string $AdditionalAttribute8 = null,
        public ?string $AdditionalAttribute9 = null,
        public ?string $AdditionalAttribute10 = null,
        public ?string $AttributeSet = null,
        public ?string $DiscountRule = null,
        public ?string $Tags = null,
        public ?string $Status = null,
        public ?string $StockLocator = null,
        public ?string $COGSAccount = null,
        public ?string $RevenueAccount = null,
        public ?string $ExpenseAccount = null,
        public ?string $InventoryAccount = null,
        public ?string $PurchaseTaxRule = null,
        public ?string $SaleTaxRule = null,
        public ?string $LastModifiedOn = null,
        public ?bool $Sellable = null,
        public ?string $PickZones = null,
        public ?bool $BillOfMaterial = null,
        public ?bool $AutoAssembly = null,
        public ?bool $AutoDisassembly = null,
        public ?float $QuantityToProduce = null,
        public ?string $AssemblyInstructionURL = null,
        public ?string $AssemblyCostEstimationMethod = null,
        public ?string $BOMType = null,
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
