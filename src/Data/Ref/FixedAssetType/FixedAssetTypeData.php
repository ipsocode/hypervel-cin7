<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\FixedAssetType;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\AveragingMethod;
use Ipsocode\Cin7\Enums\DepreciationMethod;

/**
 * Fixed Asset Type, one entry of `FixedAssetTypeList` in every `ref/fixedassettype` response: the
 * table with its `FixedAssetTypeID` and the read-only names of the linked accounts. The bodies of
 * POST and PUT are `FixedAssetTypePostData` and `FixedAssetTypePutData`.
 *
 * @see docs/data.md
 */
final class FixedAssetTypeData extends AbstractFixedAssetTypeData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        DepreciationMethod $DepreciationMethod,
        AveragingMethod $AveragingMethod,
        string $AssetAccountCode,
        string $AccumulatedDepreciationAccountCode,
        #[Uuid]
        public ?string $FixedAssetTypeID = null,
        public ?string $AssetAccountName = null,
        public ?string $AccumulatedDepreciationAccountName = null,
        public ?string $DepreciationExpenseAccountName = null,
    ) {
        parent::__construct($Name, $DepreciationMethod, $AveragingMethod, $AssetAccountCode, $AccumulatedDepreciationAccountCode);
    }
}
