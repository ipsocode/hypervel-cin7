<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductFamily;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Enums\CostingMethod;

/**
 * Product Family, one entry of `ProductFamilies` in every `productFamily` response: the table with
 * its `ID` and its read-only `Option1Values` to `Option3Values`, `LastModifiedOn`, `Attachments`
 * and `CountryOfOriginCode`. The bodies of POST and PUT are `ProductFamilyPostData` and
 * `ProductFamilyPutData`.
 *
 * @see docs/data.md
 */
final class ProductFamilyData extends AbstractProductFamilyData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttachmentLineData> $Attachments
     */
    public function __construct(
        string $SKU,
        string $Name,
        string $Category,
        CostingMethod $CostingMethod,
        string $DefaultLocation,
        string $UOM,
        string $Option1Name,
        #[Uuid]
        public ?string $ID = null,
        public ?string $Option1Values = null,
        public ?string $Option2Values = null,
        public ?string $Option3Values = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
        public ?string $CountryOfOriginCode = null,
    ) {
        parent::__construct($SKU, $Name, $Category, $CostingMethod, $DefaultLocation, $UOM, $Option1Name);
    }
}
