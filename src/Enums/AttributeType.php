<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of an attribute of an attribute set.
 *
 * @see docs/data.md
 */
enum AttributeType: string
{
    case NotUsed = 'Not used';
    case Text = 'Text';
    case Checkbox = 'Checkbox';
    case List = 'List';
    case Date = 'Date';
    case Numeric = 'Numeric';
}
