<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Contacts;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Me Contact, one entry of `MeContactsList` in every `me/contacts` response: the Me Contact table
 * with its `ContactID`. The bodies of POST and PUT are `MeContactPostData` and `MeContactPutData`.
 *
 * The reference's PUT response example also carries `CRMID` and `ReferenceCount`, which no table
 * lists.
 *
 * @see docs/data.md
 */
final class MeContactData extends AbstractMeContactData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ContactID = null,
        public ?string $CRMID = null,
        public ?int $ReferenceCount = null,
    ) {
        parent::__construct($Name);
    }
}
