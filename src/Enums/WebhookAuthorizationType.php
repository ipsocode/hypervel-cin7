<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How Cin7 authorizes itself to a webhook's callback URL.
 *
 * @see src/docs/data.md
 */
enum WebhookAuthorizationType: string
{
    case NoAuth = 'noauth';
    case BasicAuth = 'basicauth';
    case BearerAuth = 'bearerauth';
}
