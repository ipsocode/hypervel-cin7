<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\FixedAssetType;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypeData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/fixedassettype`, body is a `FixedAssetTypePutData` and carries the fixed asset type's
 * `FixedAssetTypeID`; the response is the list envelope holding the saved fixed asset type.
 *
 * @extends WriteRequest<FixedAssetTypeData>
 */
final class PutFixedAssetType extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/fixedassettype';
    }

    public function createDtoFromResponse(Response $response): FixedAssetTypeData
    {
        return FixedAssetTypeData::from($response->json('FixedAssetTypeList.0'))->setResponse($response);
    }
}
