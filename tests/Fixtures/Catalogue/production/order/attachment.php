<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentPutData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentsData;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\DeleteProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\GetProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\PostProductionOrderAttachment;
use Ipsocode\Cin7\Requests\Production\Order\Attachment\PutProductionOrderAttachment;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/attachment`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PostProductionOrderAttachment::class => [
            PostProductionOrderAttachment::class,
            [['FileName' => 'a.txt'], 'productionOrderId' => $id],
            Method::POST,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderID' => $id],
            ['FileName' => 'a.txt'],
        ],
        PostProductionOrderAttachment::class . ' with data' => [
            PostProductionOrderAttachment::class,
            [fn (): ProductionOrderAttachmentData => ProductionOrderAttachmentData::from(Cin7Payloads::load('production/order/attachment', 'post.request')), 'productionOrderId' => $id],
            Method::POST,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderID' => $id],
            [
                'FileName' => 'Production Order Traveler-v2.docx',
                'Date' => '2021-02-24T08:15:56.903Z',
                'ContentType' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'IsProcessed' => true,
                'UserName' => 'vitaliy.l@dearsystems.com',
                'Content' => 'QUxURVIgICBWSUVXIFtkYm9dLlt2UHJvZHVjdGlvblJ1bk9wZXJhdGlvbl0NCkFTDQoNCi0tID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PQ0KLS0gQ2hhbmdlIEF1dGhvcjogVml0YWxpeSBMdWthbmluDQotLSBVcGRhdGVkOiAyNC4xMS4yMDIwDQotLSBEZXNjcmlwdGlvbjogQWRkIFByb2R1Y3Rpb25PcmRlck9wZXJhdGlvbi0+V29ya0NlbnRlckNvTWFuUHJvY3VyZW1lbnRUeXBlIGNvbHVtbiAoRERSLTE0NzkxKQ0KLS0gPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09DQoNClNFTEVDVCANCglkLltPcGVyYXRpb25JRF0sDQoJW1Byb2R1Y3Rpb25SdW5JRF0sDQoJZC5bT3JkZXJdLA0KCWQuW05hbWVdLA0KCVtTdGF0dXNdLA0KCVtQbGFubmVkVGltZV0sDQoJW0FjdHVhbFRpbWVdLA0KCWQuW1VuaXRzUGVyQ3ljbGVdLA0KCVtTdGFydERhdGVdLA0KCVtFbmREYXRlXSwNCglbRHVlRGF0ZV0sDQoJZC5bV29ya0NlbnRlcklEXSwNCglkLlNvdXJjZU9wZXJhdGlvbklELA0KCXcuTmFtZSBhcyBbV29ya0NlbnRlck5hbWVdLA0KCXcuQ29kZSBhcyBbV29ya0NlbnRlckNvZGVdLA0KCW8uW1dvcmtDZW50ZXJDb01hblByb2N1cmVtZW50VHlwZV0sDQoJZC5PcGVyYXRpb25UeXBlLA0KCWQuSXNEcm9wU2hpcA0KCUZST00gDQoJCVtQcm9kdWN0aW9uUnVuT3BlcmF0aW9uXSBkDQoJCUxFRlQgSk9JTiBbV29ya0NlbnRlcl0gdyBPTiB3LldvcmtDZW50ZXJJRCA9IGQuV29ya0NlbnRlcklEIEFORCB3LlRlbmFudElEID0gZC5UZW5hbnRJRA0KCQlJTk5FUiBKT0lOIFtQcm9kdWN0aW9uT3JkZXJPcGVyYXRpb25dIG8gd2l0aChub2xvY2spIE9OIG8uT3BlcmF0aW9uSUQ9IGQuU291cmNlT3BlcmF0aW9uSUQgQU5EIG8uVGVuYW50SUQgPSBkLlRlbmFudElEDQoJV0hFUkUgZC5UZW5hbnRJRCA9IFNZU1RFTV9VU0VSDQpHTw==',
            ],
        ],
        PutProductionOrderAttachment::class => [
            PutProductionOrderAttachment::class,
            [['AttachmentID' => $id, 'IsProcessed' => true]],
            Method::PUT,
            '/ExternalApi/v2/production/order/attachment',
            [],
            ['AttachmentID' => $id, 'IsProcessed' => true],
        ],
        PutProductionOrderAttachment::class . ' with data' => [
            PutProductionOrderAttachment::class,
            [fn (): ProductionOrderAttachmentPutData => ProductionOrderAttachmentPutData::from(Cin7Payloads::load('production/order/attachment', 'put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/order/attachment',
            [],
            [
                'AttachmentID' => '371e225d-00d5-449d-9443-a2124ebc8a70',
                'IsProcessed' => true,
            ],
        ],
        DeleteProductionOrderAttachment::class => [
            DeleteProductionOrderAttachment::class,
            [$id],
            Method::DELETE,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderAttachmentID' => $id],
            null,
        ],
        GetProductionOrderAttachment::class => [
            GetProductionOrderAttachment::class,
            [$id, 'returnAttachmentsContent' => true],
            Method::GET,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
    ],
    'resources' => [
        'production order attachment post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->post(['FileName' => 'a.txt'], $id),
            PostProductionOrderAttachment::class,
        ],
        'production order attachment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->put(['AttachmentID' => $id, 'IsProcessed' => true]),
            PutProductionOrderAttachment::class,
        ],
        'production order attachment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->delete($id),
            DeleteProductionOrderAttachment::class,
        ],
        'production order attachment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->get($id, returnAttachmentsContent: true),
            GetProductionOrderAttachment::class,
        ],
    ],
    'dtos' => [
        PostProductionOrderAttachment::class => [PostProductionOrderAttachment::class, [[], $id], Cin7Payloads::load('production/order/attachment', 'post.response'), ProductionOrderAttachmentData::class, ''],
        PutProductionOrderAttachment::class => [PutProductionOrderAttachment::class, [[]], Cin7Payloads::load('production/order/attachment', 'put.response'), ProductionOrderAttachmentData::class, ''],
        GetProductionOrderAttachment::class => [GetProductionOrderAttachment::class, [$id], Cin7Payloads::load('production/order/attachment', 'get.response'), ProductionOrderAttachmentsData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderAttachmentData production/order/attachment' => [ProductionOrderAttachmentData::class, PostProductionOrderAttachment::class, Cin7Payloads::load('production/order/attachment', 'post.request')],
        'ProductionOrderAttachmentPutData production/order/attachment' => [ProductionOrderAttachmentPutData::class, PutProductionOrderAttachment::class, Cin7Payloads::load('production/order/attachment', 'put.request')],
    ],
];
