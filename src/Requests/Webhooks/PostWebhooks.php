<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Webhooks;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Webhooks\WebhookData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST webhooks`, body is a `WebhookPostData`; the response is a `Webhooks` list holding the saved webhook.
 *
 * @extends WriteRequest<list<WebhookData>>
 */
final class PostWebhooks extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'webhooks';
    }

    /**
     * @return list<WebhookData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): WebhookData => WebhookData::from($item)->setResponse($response),
            array_values($response->json('Webhooks')),
        );
    }
}
