<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Webhooks\WebhookData;
use Ipsocode\Cin7\Data\Webhooks\WebhookPostData;
use Ipsocode\Cin7\Data\Webhooks\WebhookPutData;
use Ipsocode\Cin7\Requests\Webhooks\DeleteWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\GetWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\PostWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\PutWebhooks;

/**
 * `webhooks`, the callbacks Cin7 sends when an event happens.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class WebhooksResource extends BaseResource
{
    /**
     * Every webhook; the reference has no paging.
     *
     * @return Response<list<WebhookData>>
     */
    public function get(): Response
    {
        return $this->connector->send(new GetWebhooks);
    }

    /**
     * @param array<string, mixed>|WebhookPostData $body
     * @return Response<list<WebhookData>>
     */
    public function post(array|WebhookPostData $body): Response
    {
        return $this->connector->send(new PostWebhooks($body));
    }

    /**
     * @param array<string, mixed>|WebhookPutData $body
     * @return Response<list<WebhookData>>
     */
    public function put(array|WebhookPutData $body): Response
    {
        return $this->connector->send(new PutWebhooks($body));
    }

    /**
     * Delete the webhook with this ID.
     *
     * @return Response<null>
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteWebhooks($id));
    }
}
