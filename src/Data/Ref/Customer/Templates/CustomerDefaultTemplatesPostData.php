<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Customer\Templates;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * The body of `ref/customer/templates` POST: the `CustomerTemplates` to set, each a
 * `CustomerDefaultTemplateData`. The reference has no table of its own for it.
 *
 * @see docs/data.md
 */
final class CustomerDefaultTemplatesPostData extends Data
{
    /**
     * @param list<CustomerDefaultTemplateData> $CustomerTemplates
     */
    public function __construct(
        #[DataCollectionOf(CustomerDefaultTemplateData::class)]
        public array $CustomerTemplates,
    ) {
    }
}
