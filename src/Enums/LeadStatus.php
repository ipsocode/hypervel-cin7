<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The status of a lead.
 *
 * @see docs/data.md
 */
enum LeadStatus: string
{
    case New = 'NEW';
    case AttemptingToContact = 'ATTEMPTING TO CONTACT';
    case Qualified = 'QUALIFIED';
    case Disqualified = 'DISQUALIFIED';
}
