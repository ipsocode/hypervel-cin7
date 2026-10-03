<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Webhooks;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\WebhookAuthorizationType;
use Ipsocode\Cin7\Enums\WebhookType;

/**
 * The fields of the webhook table: the response of `webhooks` and the body of its POST and PUT. Each is
 * a final child that adds its `ID`, or none. Every webhook needs its `Type`, `IsActive`, `ExternalURL`
 * and `ExternalAuthorizationType`, so each child passes them to this constructor. The credentials are
 * required by the authorisation type: a user name and password for `basicauth`, a token for
 * `bearerauth`.
 *
 * @see docs/data.md
 */
abstract class AbstractWebhookData extends Data
{
    public ?string $Name = null;

    #[RequiredIf('ExternalAuthorizationType', WebhookAuthorizationType::BasicAuth)]
    public ?string $ExternalUserName = null;

    #[RequiredIf('ExternalAuthorizationType', WebhookAuthorizationType::BasicAuth)]
    public ?string $ExternalPassword = null;

    #[RequiredIf('ExternalAuthorizationType', WebhookAuthorizationType::BearerAuth)]
    public ?string $ExternalBearerToken = null;

    /**
     * @var null|list<WebhookHeaderData>
     */
    #[DataCollectionOf(WebhookHeaderData::class)]
    public ?array $ExternalHeaders = null;

    public function __construct(
        public WebhookType $Type,
        public bool $IsActive,
        public string $ExternalURL,
        public WebhookAuthorizationType $ExternalAuthorizationType,
    ) {
    }
}
