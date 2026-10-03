<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Webhooks;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\WebhookAuthorizationType;
use Ipsocode\Cin7\Enums\WebhookType;

/**
 * Webhook, one entry of `Webhooks` in every response of `webhooks`: the table with its `ID`. The
 * bodies of POST and PUT are `WebhookPostData` and `WebhookPutData`.
 *
 * @see docs/data.md
 */
final class WebhookData extends AbstractWebhookData implements WithResponse
{
    use HasResponse;

    public function __construct(
        WebhookType $Type,
        bool $IsActive,
        string $ExternalURL,
        WebhookAuthorizationType $ExternalAuthorizationType,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Type, $IsActive, $ExternalURL, $ExternalAuthorizationType);
    }
}
