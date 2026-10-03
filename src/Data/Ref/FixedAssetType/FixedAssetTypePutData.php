<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\FixedAssetType;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\AveragingMethod;
use Ipsocode\Cin7\Enums\DepreciationMethod;

/**
 * The body of `ref/fixedassettype` PUT: the table with the `FixedAssetTypeID` of the type to
 * change, which PUT requires. The POST body is `FixedAssetTypePostData`.
 *
 * @see docs/data.md
 */
final class FixedAssetTypePutData extends AbstractFixedAssetTypeData
{
    public function __construct(
        string $Name,
        DepreciationMethod $DepreciationMethod,
        AveragingMethod $AveragingMethod,
        string $AssetAccountCode,
        string $AccumulatedDepreciationAccountCode,
        #[Uuid]
        public string $FixedAssetTypeID,
    ) {
        parent::__construct($Name, $DepreciationMethod, $AveragingMethod, $AssetAccountCode, $AccumulatedDepreciationAccountCode);
    }
}
