<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Webhooks;

use Hypervel\Data\Data;

/**
 * Webhook Header, one entry of a webhook's `ExternalHeaders`: a `Key` and its `Value`, sent with each
 * callback. The reference marks neither as required.
 *
 * @see docs/data.md
 */
final class WebhookHeaderData extends Data
{
    public function __construct(
        public ?string $Key = null,
        public ?string $Value = null,
    ) {
    }
}
