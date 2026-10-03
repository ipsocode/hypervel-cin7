<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Webhooks;

/**
 * The body of `webhooks` POST: the table without the `ID`, which Cin7 assigns, and the `Name`, which it
 * writes. The PUT body is `WebhookPutData`.
 *
 * @see docs/data.md
 */
final class WebhookPostData extends AbstractWebhookData
{
}
