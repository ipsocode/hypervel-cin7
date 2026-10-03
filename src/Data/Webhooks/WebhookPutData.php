<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Webhooks;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\WebhookAuthorizationType;
use Ipsocode\Cin7\Enums\WebhookType;

/**
 * The body of `webhooks` PUT: the table with the `ID` of the webhook to change, which PUT requires.
 * The POST body is `WebhookPostData`.
 *
 * @see docs/data.md
 */
final class WebhookPutData extends AbstractWebhookData
{
    public function __construct(
        WebhookType $Type,
        bool $IsActive,
        string $ExternalURL,
        WebhookAuthorizationType $ExternalAuthorizationType,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Type, $IsActive, $ExternalURL, $ExternalAuthorizationType);
    }
}
