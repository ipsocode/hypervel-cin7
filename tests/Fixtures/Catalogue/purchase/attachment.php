<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentPostData;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentsData;
use Ipsocode\Cin7\Requests\Purchase\Attachment\DeletePurchaseAttachment;
use Ipsocode\Cin7\Requests\Purchase\Attachment\GetPurchaseAttachment;
use Ipsocode\Cin7\Requests\Purchase\Attachment\PostPurchaseAttachment;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `purchase/attachment`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetPurchaseAttachment::class => [
            GetPurchaseAttachment::class,
            ['02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            Method::GET,
            '/ExternalApi/v2/purchase/attachment',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        PostPurchaseAttachment::class => [
            PostPurchaseAttachment::class,
            [['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg']],
            Method::POST,
            '/ExternalApi/v2/purchase/attachment',
            [],
            ['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        PostPurchaseAttachment::class . ' with data' => [
            PostPurchaseAttachment::class,
            [fn (): PurchaseAttachmentPostData => PurchaseAttachmentPostData::from(Cin7Payloads::load('purchase/attachment', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/purchase/attachment',
            [],
            Cin7Payloads::load('purchase/attachment', 'post.request'),
        ],
        DeletePurchaseAttachment::class => [
            DeletePurchaseAttachment::class,
            ['990d6602-2f4a-432b-97e1-04a2f51f7036'],
            Method::DELETE,
            '/ExternalApi/v2/purchase/attachment',
            ['ID' => '990d6602-2f4a-432b-97e1-04a2f51f7036'],
            null,
        ],
    ],
    'resources' => [
        'purchase attachment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->attachment()->get('02b08cd2-51d2-41e6-ab97-85bcd13e7136'),
            GetPurchaseAttachment::class,
            Method::GET,
            '/ExternalApi/v2/purchase/attachment',
            ['TaskID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136'],
            null,
        ],
        'purchase attachment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->attachment()->post(['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'Content' => 'AAAA']),
            PostPurchaseAttachment::class,
            Method::POST,
            '/ExternalApi/v2/purchase/attachment',
            [],
            ['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'Content' => 'AAAA'],
        ],
        'purchase attachment post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->attachment()->post(PurchaseAttachmentPostData::from(['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'])),
            PostPurchaseAttachment::class,
            Method::POST,
            '/ExternalApi/v2/purchase/attachment',
            [],
            ['PurchaseID' => '02b08cd2-51d2-41e6-ab97-85bcd13e7136', 'FileName' => 'Test', 'FileDownloadUrl' => 'https://files.example/test.jpg'],
        ],
        'purchase attachment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->purchase()->attachment()->delete('990d6602-2f4a-432b-97e1-04a2f51f7036'),
            DeletePurchaseAttachment::class,
            Method::DELETE,
            '/ExternalApi/v2/purchase/attachment',
            ['ID' => '990d6602-2f4a-432b-97e1-04a2f51f7036'],
            null,
        ],
    ],
    'dtos' => [
        GetPurchaseAttachment::class => [GetPurchaseAttachment::class, ['task-1'], Cin7Payloads::load('purchase/attachment', 'get.response'), PurchaseAttachmentsData::class, ''],
        PostPurchaseAttachment::class => [PostPurchaseAttachment::class, [[]], Cin7Payloads::load('purchase/attachment', 'post.response'), PurchaseAttachmentsData::class, ''],
        DeletePurchaseAttachment::class => [DeletePurchaseAttachment::class, ['attachment-1'], Cin7Payloads::load('purchase/attachment', 'delete.response'), PurchaseAttachmentsData::class, ''],
    ],
    'bodies' => [
        PurchaseAttachmentPostData::class => [PurchaseAttachmentPostData::class, Cin7Payloads::load('purchase/attachment', 'post.request')],
    ],
    'missing' => [
        'purchase attachments without TaskID' => [PurchaseAttachmentsData::class, Arr::except(Cin7Payloads::load('purchase/attachment', 'get.response'), 'TaskID')],
        'purchase attachment POST without PurchaseID' => [PurchaseAttachmentPostData::class, Arr::except(Cin7Payloads::load('purchase/attachment', 'post.request'), 'PurchaseID')],
        'purchase attachment POST without FileName' => [PurchaseAttachmentPostData::class, Arr::except(Cin7Payloads::load('purchase/attachment', 'post.request'), 'FileName')],
    ],
    'required' => [
        PurchaseAttachmentsData::class => ['TaskID'],
        PurchaseAttachmentPostData::class => ['PurchaseID', 'FileName'],
    ],
];
