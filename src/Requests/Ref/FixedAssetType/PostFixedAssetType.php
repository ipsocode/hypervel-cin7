<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\FixedAssetType;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypeData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/fixedassettype`, body is a `FixedAssetTypePostData`; the response is the list envelope
 * holding the saved fixed asset type.
 *
 * @extends WriteRequest<FixedAssetTypeData>
 */
final class PostFixedAssetType extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = FixedAssetTypeData::class;

    protected ?string $dtoPath = 'FixedAssetTypeList.0';

    public function resolveEndpoint(): string
    {
        return 'ref/fixedassettype';
    }
}
