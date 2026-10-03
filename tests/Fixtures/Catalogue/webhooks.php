<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Webhooks\WebhookData;
use Ipsocode\Cin7\Data\Webhooks\WebhookPostData;
use Ipsocode\Cin7\Data\Webhooks\WebhookPutData;
use Ipsocode\Cin7\Requests\Webhooks\DeleteWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\GetWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\PostWebhooks;
use Ipsocode\Cin7\Requests\Webhooks\PutWebhooks;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `webhooks`; tests/Catalogue.php merges every file's rows by kind.

$id = '1cf8cb83-bf39-494b-87f9-1252b684d6d5';
$uri = '/ExternalApi/v2/webhooks';
$post = ['Type' => 'Sale/Created', 'IsActive' => true, 'ExternalURL' => 'https://example.test/hook', 'ExternalAuthorizationType' => 'noauth'];
$put = $post + ['ID' => $id];
$putSent = ['ID' => $id] + $post;

return [
    'requests' => [
        GetWebhooks::class => [GetWebhooks::class, [], Method::GET, $uri, [], null],
        PostWebhooks::class => [PostWebhooks::class, [$post], Method::POST, $uri, [], $post],
        PutWebhooks::class => [PutWebhooks::class, [$put], Method::PUT, $uri, [], $put],
        DeleteWebhooks::class => [DeleteWebhooks::class, [$id], Method::DELETE, $uri, ['ID' => $id], null],
        PostWebhooks::class . ' with data' => [PostWebhooks::class, [fn (): WebhookPostData => WebhookPostData::from($post)], Method::POST, $uri, [], $post],
        PutWebhooks::class . ' with data' => [PutWebhooks::class, [fn (): WebhookPutData => WebhookPutData::from($put)], Method::PUT, $uri, [], $putSent],
    ],
    'resources' => [
        'webhooks get' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->get(), GetWebhooks::class, Method::GET, $uri, [], null],
        'webhooks post' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->post($post), PostWebhooks::class, Method::POST, $uri, [], $post],
        'webhooks post with data' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->post(WebhookPostData::from($post)), PostWebhooks::class, Method::POST, $uri, [], $post],
        'webhooks put' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->put($put), PutWebhooks::class, Method::PUT, $uri, [], $put],
        'webhooks put with data' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->put(WebhookPutData::from($put)), PutWebhooks::class, Method::PUT, $uri, [], $putSent],
        'webhooks delete' => [fn (Cin7Connector $cin7): mixed => $cin7->webhooks()->delete($id), DeleteWebhooks::class, Method::DELETE, $uri, ['ID' => $id], null],
    ],
    'dtos' => [
        GetWebhooks::class => [GetWebhooks::class, [], Cin7Payloads::load('webhooks', 'get.response'), WebhookData::class, 'Webhooks'],
        PostWebhooks::class => [PostWebhooks::class, [[]], Cin7Payloads::load('webhooks', 'post.response'), WebhookData::class, 'Webhooks'],
        PutWebhooks::class => [PutWebhooks::class, [[]], Cin7Payloads::load('webhooks', 'put.response'), WebhookData::class, 'Webhooks'],
    ],
    'bodies' => [
        WebhookPostData::class => [WebhookPostData::class, Cin7Payloads::load('webhooks', 'post.request')],
        WebhookPutData::class => [WebhookPutData::class, Cin7Payloads::load('webhooks', 'put.request')],
    ],
    'missing' => [
        'webhook POST without Type' => [WebhookPostData::class, Arr::except($post, 'Type')],
        'webhook POST without IsActive' => [WebhookPostData::class, Arr::except($post, 'IsActive')],
        'webhook POST without ExternalURL' => [WebhookPostData::class, Arr::except($post, 'ExternalURL')],
        'webhook POST without ExternalAuthorizationType' => [WebhookPostData::class, Arr::except($post, 'ExternalAuthorizationType')],
        'webhook PUT without ID' => [WebhookPutData::class, Arr::except($put, 'ID')],
    ],
    'required' => [
        WebhookData::class => ['Type', 'IsActive', 'ExternalURL', 'ExternalAuthorizationType'],
        WebhookPostData::class => ['Type', 'IsActive', 'ExternalURL', 'ExternalAuthorizationType'],
        WebhookPutData::class => ['Type', 'IsActive', 'ExternalURL', 'ExternalAuthorizationType', 'ID'],
    ],
];
