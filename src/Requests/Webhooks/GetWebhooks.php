<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Webhooks;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Webhooks\WebhookData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET webhooks`, every webhook of the account: the reference takes no page or limit, and the response
 * is a `Webhooks` list.
 *
 * @extends Cin7Request<list<WebhookData>>
 */
final class GetWebhooks extends Cin7Request
{
    protected Method $method = Method::GET;

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
