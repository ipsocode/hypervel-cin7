<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Attributes;

use Attribute;
use Hypervel\Data\Attributes\Validation\CustomValidationAttribute;
use Hypervel\Data\Support\Validation\ValidationPath;

/**
 * A Cin7 DateTime: ISO 8601 in UTC, `yyyy-MM-ddTHH:mm:ss`, with up to seven fraction digits and an
 * optional `Z`, as the reference's Date Format section and examples write it. An offset is
 * rejected, since Cin7 reads every date as UTC.
 *
 * @see docs/data.md
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class DateTime extends CustomValidationAttribute
{
    /**
     * The shape Cin7 reads; the `date` rule then rejects an impossible date.
     */
    public const string PATTERN = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{1,7})?Z?$/';

    /**
     * Get the Validator rules.
     *
     * @return list<string>
     */
    public function getRules(ValidationPath $path): array
    {
        return ['regex:' . self::PATTERN, 'date'];
    }
}
