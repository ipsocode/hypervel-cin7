<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/tax`, body is a Tax rule and carries `ID`.
 *
 * @extends WriteRequest<mixed>
 */
final class PutTax extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }
}
