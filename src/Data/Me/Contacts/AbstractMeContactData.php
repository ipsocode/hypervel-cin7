<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Contacts;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\ContactType;

/**
 * The fields of the Me Contact table: the response of `me/contacts` and the body of its POST and
 * PUT. Each is a final child that adds its `ContactID`, or none.
 *
 * Every contact needs its `Name`, so each child passes it to this constructor; the optional
 * fields declared here are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractMeContactData extends Data
{
    #[Max(50)]
    public ?string $Phone = null;

    #[Max(50)]
    public ?string $Fax = null;

    #[Max(256)]
    public ?string $Email = null;

    #[Max(256)]
    public ?string $Website = null;

    #[Max(256)]
    public ?string $Comment = null;

    public ?ContactType $Type = null;

    public ?bool $DefaultForType = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
    ) {
    }
}
