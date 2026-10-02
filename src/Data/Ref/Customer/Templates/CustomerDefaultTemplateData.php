<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Customer\Templates;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Customer Default Template, one entry of `CustomerTemplates`: the template a customer uses by
 * default. The table writes both ids as "Yes*": needed in a body's list, which is every use.
 *
 * @see docs/data.md
 */
final class CustomerDefaultTemplateData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public string $CustomerID,
        #[Uuid]
        public string $TemplateID,
    ) {
    }
}
