<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Contacts;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `me/contacts` PUT: the Me Contact table with the `ContactID` of the contact to
 * change, which PUT requires. The POST body is `MeContactPostData`.
 *
 * @see docs/data.md
 */
final class MeContactPutData extends AbstractMeContactData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ContactID,
    ) {
        parent::__construct($Name);
    }
}
