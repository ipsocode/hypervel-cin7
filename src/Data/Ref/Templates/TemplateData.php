<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Templates;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Template, one entry of `Templates` in a `ref/templates` list: a document template, by `TemplateID`,
 * with its `Type`, `Name` and the email subject and BCC address it carries. The reference has no POST
 * or PUT, and requires none of the fields.
 *
 * @see docs/data.md
 */
final class TemplateData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $TemplateID = null,
        public ?string $Type = null,
        public ?string $Name = null,
        public ?string $EmailSubject = null,
        public ?string $CustomEmail = null,
    ) {
    }
}
