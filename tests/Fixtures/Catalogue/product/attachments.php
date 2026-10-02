<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Product\Attachments\ProductAttachmentPostData;
use Ipsocode\Cin7\Requests\Product\Attachments\DeleteProductAttachments;
use Ipsocode\Cin7\Requests\Product\Attachments\GetProductAttachments;
use Ipsocode\Cin7\Requests\Product\Attachments\PostProductAttachments;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `product/attachments`; tests/Catalogue.php merges every file's rows by kind.

$url = ['ProductID' => '76755c09-60de-483c-a63b-18fd12c2d932', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'];

return [
    'requests' => [
        GetProductAttachments::class => [
            GetProductAttachments::class,
            ['76755c09-60de-483c-a63b-18fd12c2d932'],
            Method::GET,
            '/ExternalApi/v2/product/attachments',
            ['ProductID' => '76755c09-60de-483c-a63b-18fd12c2d932'],
            null,
        ],
        PostProductAttachments::class => [
            PostProductAttachments::class,
            [$url],
            Method::POST,
            '/ExternalApi/v2/product/attachments',
            [],
            $url,
        ],
        DeleteProductAttachments::class => [
            DeleteProductAttachments::class,
            ['3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            Method::DELETE,
            '/ExternalApi/v2/product/attachments',
            ['ID' => '3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            null,
        ],
        PostProductAttachments::class . ' with data' => [
            PostProductAttachments::class,
            [fn (): ProductAttachmentPostData => ProductAttachmentPostData::from(Cin7Payloads::load('product/attachments', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/product/attachments',
            [],
            Cin7Payloads::load('product/attachments', 'post.request'),
        ],
    ],
    'resources' => [
        'product attachments get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->attachments()->get('76755c09-60de-483c-a63b-18fd12c2d932'),
            GetProductAttachments::class,
            Method::GET,
            '/ExternalApi/v2/product/attachments',
            ['ProductID' => '76755c09-60de-483c-a63b-18fd12c2d932'],
            null,
        ],
        'product attachments post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->attachments()->post($url),
            PostProductAttachments::class,
            Method::POST,
            '/ExternalApi/v2/product/attachments',
            [],
            $url,
        ],
        'product attachments post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->attachments()->post(ProductAttachmentPostData::from([...$url, 'IsDefault' => true])),
            PostProductAttachments::class,
            Method::POST,
            '/ExternalApi/v2/product/attachments',
            [],
            ['ProductID' => '76755c09-60de-483c-a63b-18fd12c2d932', 'FileName' => 'Test', 'IsDefault' => true, 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        'product attachments delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->product()->attachments()->delete('3ba9b33c-662a-4bed-935a-9e13e7535c88'),
            DeleteProductAttachments::class,
            Method::DELETE,
            '/ExternalApi/v2/product/attachments',
            ['ID' => '3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            null,
        ],
    ],
    'dtos' => [
        GetProductAttachments::class => [GetProductAttachments::class, ['76755c09-60de-483c-a63b-18fd12c2d932'], Cin7Payloads::load('product/attachments', 'get.response'), AttachmentLineData::class, ''],
        PostProductAttachments::class => [PostProductAttachments::class, [[]], Cin7Payloads::load('product/attachments', 'post.response'), AttachmentLineData::class, ''],
        DeleteProductAttachments::class => [DeleteProductAttachments::class, ['3ba9b33c-662a-4bed-935a-9e13e7535c88'], Cin7Payloads::load('product/attachments', 'delete.response'), AttachmentLineData::class, ''],
    ],
    'bodies' => [
        ProductAttachmentPostData::class => [ProductAttachmentPostData::class, Cin7Payloads::load('product/attachments', 'post.request')],
    ],
    'missing' => [
        'product attachment POST without ProductID' => [ProductAttachmentPostData::class, Arr::except(Cin7Payloads::load('product/attachments', 'post.request'), 'ProductID')],
        'product attachment POST without FileName' => [ProductAttachmentPostData::class, Arr::except(Cin7Payloads::load('product/attachments', 'post.request'), 'FileName')],
    ],
    'required' => [
        ProductAttachmentPostData::class => ['ProductID', 'FileName'],
    ],
];
