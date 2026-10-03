<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Webhooks;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Webhooks\WebhookData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT webhooks`, body is a `WebhookPutData` and carries the webhook's `ID`; the response is a `Webhooks` list holding the saved webhook.
 *
 * @extends WriteRequest<list<WebhookData>>
 */
final class PutWebhooks extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'webhooks';
    }

    /**
     * @return list<WebhookData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(WebhookData::class, $response, $response->json('Webhooks'));
    }
}
