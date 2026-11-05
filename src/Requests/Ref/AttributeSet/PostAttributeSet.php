<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\AttributeSet;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/attributeset`, body is an `AttributeSetPostData`; the response is the saved attribute
 * set, not a list.
 *
 * @extends WriteRequest<AttributeSetData>
 */
final class PostAttributeSet extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = AttributeSetData::class;

    public function resolveEndpoint(): string
    {
        return 'ref/attributeset';
    }
}
