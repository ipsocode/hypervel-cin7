<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Contacts;

/**
 * The body of `me/contacts` POST: the Me Contact table without the `ContactID` Cin7 assigns. The
 * PUT body is `MeContactPutData`.
 *
 * @see docs/data.md
 */
final class MeContactPostData extends AbstractMeContactData
{
    public function __construct(
        string $Name,
    ) {
        parent::__construct($Name);
    }
}
