<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\AttributeSet;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\AttributeType;

/**
 * Attribute Set, the response of every `ref/attributeset` GET, POST and PUT: the table with its
 * `ID`, its first attribute and its read-only `Attributes`. The bodies of POST and PUT are
 * `AttributeSetPostData` and `AttributeSetPutData`.
 *
 * @see docs/data.md
 */
final class AttributeSetData extends AbstractAttributeSetData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttributeSetLineData> $Attributes
     */
    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ID = null,
        #[Max(50)]
        public ?string $Attribute1Name = null,
        public ?AttributeType $Attribute1Type = null,
        public ?string $Attribute1Values = null,
        #[DataCollectionOf(AttributeSetLineData::class)]
        public ?array $Attributes = null,
    ) {
        parent::__construct($Name);
    }
}
