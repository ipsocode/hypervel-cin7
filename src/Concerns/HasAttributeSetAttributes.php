<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Concerns;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Enums\AttributeType;

/**
 * `Attribute2Name` to `Attribute10Values` of an attribute set: nine optional attributes, each a
 * name, a type and a comma-delimited list of values. `Attribute1…` is required on a write, so each
 * class takes it as a constructor argument.
 *
 * @see docs/data.md
 */
trait HasAttributeSetAttributes
{
    #[Max(50)]
    public ?string $Attribute2Name = null;

    public ?AttributeType $Attribute2Type = null;

    public ?string $Attribute2Values = null;

    #[Max(50)]
    public ?string $Attribute3Name = null;

    public ?AttributeType $Attribute3Type = null;

    public ?string $Attribute3Values = null;

    #[Max(50)]
    public ?string $Attribute4Name = null;

    public ?AttributeType $Attribute4Type = null;

    public ?string $Attribute4Values = null;

    #[Max(50)]
    public ?string $Attribute5Name = null;

    public ?AttributeType $Attribute5Type = null;

    public ?string $Attribute5Values = null;

    #[Max(50)]
    public ?string $Attribute6Name = null;

    public ?AttributeType $Attribute6Type = null;

    public ?string $Attribute6Values = null;

    #[Max(50)]
    public ?string $Attribute7Name = null;

    public ?AttributeType $Attribute7Type = null;

    public ?string $Attribute7Values = null;

    #[Max(50)]
    public ?string $Attribute8Name = null;

    public ?AttributeType $Attribute8Type = null;

    public ?string $Attribute8Values = null;

    #[Max(50)]
    public ?string $Attribute9Name = null;

    public ?AttributeType $Attribute9Type = null;

    public ?string $Attribute9Values = null;

    #[Max(50)]
    public ?string $Attribute10Name = null;

    public ?AttributeType $Attribute10Type = null;

    public ?string $Attribute10Values = null;
}
