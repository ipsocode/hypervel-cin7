<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\FixedAssetType;

/**
 * The body of `ref/fixedassettype` POST: the table without the `FixedAssetTypeID` Cin7 assigns
 * and the read-only account names. The PUT body is `FixedAssetTypePutData`.
 *
 * @see docs/data.md
 */
final class FixedAssetTypePostData extends AbstractFixedAssetTypeData
{
}
