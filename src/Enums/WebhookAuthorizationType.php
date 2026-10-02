<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How Cin7 authorises itself to a webhook's callback URL.
 *
 * @see docs/data.md
 */
enum WebhookAuthorizationType: string
{
    case NoAuth = 'noauth';
    case BasicAuth = 'basicauth';
    case BearerAuth = 'bearerauth';
}
