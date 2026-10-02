<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentPostData;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentsData;
use Ipsocode\Cin7\Requests\Sale\Attachment\DeleteSaleAttachment;
use Ipsocode\Cin7\Requests\Sale\Attachment\GetSaleAttachment;
use Ipsocode\Cin7\Requests\Sale\Attachment\PostSaleAttachment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `sale/attachment`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetSaleAttachment::class => [
            GetSaleAttachment::class,
            ['916ab4c0-6ccb-4c93-873d-0603859050e4'],
            Method::GET,
            '/ExternalApi/v2/sale/attachment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        PostSaleAttachment::class => [
            PostSaleAttachment::class,
            [['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg']],
            Method::POST,
            '/ExternalApi/v2/sale/attachment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        PostSaleAttachment::class . ' with data' => [
            PostSaleAttachment::class,
            [fn (): SaleAttachmentPostData => SaleAttachmentPostData::from(Cin7Payloads::load('sale/attachment', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/sale/attachment',
            [],
            Cin7Payloads::load('sale/attachment', 'post.request'),
        ],
        DeleteSaleAttachment::class => [
            DeleteSaleAttachment::class,
            ['1a103e3e-9837-4294-8cc3-640ebb4fc387'],
            Method::DELETE,
            '/ExternalApi/v2/sale/attachment',
            ['ID' => '1a103e3e-9837-4294-8cc3-640ebb4fc387'],
            null,
        ],
    ],
    'resources' => [
        'sale attachment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->attachment()->get('916ab4c0-6ccb-4c93-873d-0603859050e4'),
            GetSaleAttachment::class,
            Method::GET,
            '/ExternalApi/v2/sale/attachment',
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'],
            null,
        ],
        'sale attachment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->attachment()->post(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'Content' => 'AAAA']),
            PostSaleAttachment::class,
            Method::POST,
            '/ExternalApi/v2/sale/attachment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'Content' => 'AAAA'],
        ],
        'sale attachment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->attachment()->post(SaleAttachmentPostData::from(['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'])),
            PostSaleAttachment::class,
            Method::POST,
            '/ExternalApi/v2/sale/attachment',
            [],
            ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        'sale attachment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->sale()->attachment()->delete('1a103e3e-9837-4294-8cc3-640ebb4fc387'),
            DeleteSaleAttachment::class,
            Method::DELETE,
            '/ExternalApi/v2/sale/attachment',
            ['ID' => '1a103e3e-9837-4294-8cc3-640ebb4fc387'],
            null,
        ],
    ],
    'dtos' => [
        GetSaleAttachment::class => [GetSaleAttachment::class, ['sale-1'], Cin7Payloads::load('sale/attachment', 'get.response'), SaleAttachmentsData::class, ''],
        PostSaleAttachment::class => [PostSaleAttachment::class, [[]], Cin7Payloads::load('sale/attachment', 'get.response'), SaleAttachmentsData::class, ''],
        DeleteSaleAttachment::class => [DeleteSaleAttachment::class, ['attachment-1'], Cin7Payloads::load('sale/attachment', 'get.response'), SaleAttachmentsData::class, ''],
    ],
    'bodies' => [
        SaleAttachmentPostData::class => [SaleAttachmentPostData::class, Cin7Payloads::load('sale/attachment', 'post.request')],
    ],
    'missing' => [
        'attachments without SaleID' => [SaleAttachmentsData::class, Arr::except(Cin7Payloads::load('sale/attachment', 'get.response'), 'SaleID')],
        'attachment POST without FileName' => [SaleAttachmentPostData::class, Arr::except(Cin7Payloads::load('sale/attachment', 'post.request'), 'FileName')],
    ],
    'required' => [
        SaleAttachmentsData::class => ['SaleID'],
        SaleAttachmentPostData::class => ['SaleID', 'FileName'],
    ],
];
