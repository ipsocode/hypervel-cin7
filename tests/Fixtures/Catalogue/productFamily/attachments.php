<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\ProductFamily\Attachments\ProductFamilyAttachmentPostData;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\DeleteProductFamilyAttachments;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\GetProductFamilyAttachments;
use Ipsocode\Cin7\Requests\ProductFamily\Attachments\PostProductFamilyAttachments;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `productFamily/attachments`; tests/Catalogue.php merges every file's rows by kind.

$url = ['FamilyID' => '76755c09-60de-483c-a63b-18fd12c2d932', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'];

return [
    'requests' => [
        GetProductFamilyAttachments::class => [
            GetProductFamilyAttachments::class,
            ['76755c09-60de-483c-a63b-18fd12c2d932'],
            Method::GET,
            '/ExternalApi/v2/productFamily/attachments',
            ['FamilyID' => '76755c09-60de-483c-a63b-18fd12c2d932'],
            null,
        ],
        PostProductFamilyAttachments::class => [
            PostProductFamilyAttachments::class,
            [$url],
            Method::POST,
            '/ExternalApi/v2/productFamily/attachments',
            [],
            $url,
        ],
        DeleteProductFamilyAttachments::class => [
            DeleteProductFamilyAttachments::class,
            ['3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            Method::DELETE,
            '/ExternalApi/v2/productFamily/attachments',
            ['ID' => '3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            null,
        ],
        PostProductFamilyAttachments::class . ' with data' => [
            PostProductFamilyAttachments::class,
            [fn (): ProductFamilyAttachmentPostData => ProductFamilyAttachmentPostData::from(Cin7Payloads::load('productFamily/attachments', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/productFamily/attachments',
            [],
            Cin7Payloads::load('productFamily/attachments', 'post.request'),
        ],
    ],
    'resources' => [
        'productFamily attachments get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->attachments()->get('76755c09-60de-483c-a63b-18fd12c2d932'),
            GetProductFamilyAttachments::class,
            Method::GET,
            '/ExternalApi/v2/productFamily/attachments',
            ['FamilyID' => '76755c09-60de-483c-a63b-18fd12c2d932'],
            null,
        ],
        'productFamily attachments post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->attachments()->post($url),
            PostProductFamilyAttachments::class,
            Method::POST,
            '/ExternalApi/v2/productFamily/attachments',
            [],
            $url,
        ],
        'productFamily attachments post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->attachments()->post(ProductFamilyAttachmentPostData::from([...$url, 'IsDefault' => true])),
            PostProductFamilyAttachments::class,
            Method::POST,
            '/ExternalApi/v2/productFamily/attachments',
            [],
            ['FamilyID' => '76755c09-60de-483c-a63b-18fd12c2d932', 'FileName' => 'Test', 'IsDefault' => true, 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        'productFamily attachments delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->productFamily()->attachments()->delete('3ba9b33c-662a-4bed-935a-9e13e7535c88'),
            DeleteProductFamilyAttachments::class,
            Method::DELETE,
            '/ExternalApi/v2/productFamily/attachments',
            ['ID' => '3ba9b33c-662a-4bed-935a-9e13e7535c88'],
            null,
        ],
    ],
    'dtos' => [
        GetProductFamilyAttachments::class => [GetProductFamilyAttachments::class, ['76755c09-60de-483c-a63b-18fd12c2d932'], Cin7Payloads::load('productFamily/attachments', 'get.response'), AttachmentLineData::class, ''],
        PostProductFamilyAttachments::class => [PostProductFamilyAttachments::class, [[]], Cin7Payloads::load('productFamily/attachments', 'post.response'), AttachmentLineData::class, ''],
        DeleteProductFamilyAttachments::class => [DeleteProductFamilyAttachments::class, ['3ba9b33c-662a-4bed-935a-9e13e7535c88'], Cin7Payloads::load('productFamily/attachments', 'delete.response'), AttachmentLineData::class, ''],
    ],
    'bodies' => [
        ProductFamilyAttachmentPostData::class => [ProductFamilyAttachmentPostData::class, Cin7Payloads::load('productFamily/attachments', 'post.request')],
    ],
    'missing' => [
        'product family attachment POST without FamilyID' => [ProductFamilyAttachmentPostData::class, Arr::except(Cin7Payloads::load('productFamily/attachments', 'post.request'), 'FamilyID')],
        'product family attachment POST without FileName' => [ProductFamilyAttachmentPostData::class, Arr::except(Cin7Payloads::load('productFamily/attachments', 'post.request'), 'FileName')],
    ],
    'required' => [
        ProductFamilyAttachmentPostData::class => ['FamilyID', 'FileName'],
    ],
];
