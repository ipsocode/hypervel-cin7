<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\AttributeSet;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/attributeset`, body is an `AttributeSetPutData` and carries the attribute set's `ID`;
 * the response is the saved attribute set, not a list.
 *
 * @extends WriteRequest<AttributeSetData>
 */
final class PutAttributeSet extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = AttributeSetData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/attributeset';
    }
}
