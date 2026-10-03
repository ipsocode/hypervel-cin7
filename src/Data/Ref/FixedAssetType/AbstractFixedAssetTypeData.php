<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\FixedAssetType;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AveragingMethod;
use Ipsocode\Cin7\Enums\DepreciationMethod;

/**
 * The fields of the Fixed Asset Types table: the response of `ref/fixedassettype` and the body of
 * its POST and PUT. Each is a final child that adds its `FixedAssetTypeID`, or none.
 *
 * Every fixed asset type needs its `Name`, `DepreciationMethod`, `AveragingMethod`,
 * `AssetAccountCode` and `AccumulatedDepreciationAccountCode`, so each child passes them to this
 * constructor. The table requires `Rate` and `EffectiveLife` too, but only one of them can be set,
 * and the examples send the other as `null`, so each is nullable and `#[RequiredWithout]` the
 * other: a write body sets one. `DepreciationExpenseAccountCode` appears only in the examples.
 *
 * @see docs/data.md
 */
abstract class AbstractFixedAssetTypeData extends Data
{
    #[RequiredWithout('EffectiveLife')]
    public ?float $Rate = null;

    #[RequiredWithout('Rate')]
    public ?float $EffectiveLife = null;

    public ?string $DepreciationExpenseAccountCode = null;

    public function __construct(
        #[Max(50)]
        public string $Name,
        public DepreciationMethod $DepreciationMethod,
        public AveragingMethod $AveragingMethod,
        public string $AssetAccountCode,
        public string $AccumulatedDepreciationAccountCode,
    ) {
    }
}
