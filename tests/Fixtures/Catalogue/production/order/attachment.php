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

// The catalogue rows for `production/order/attachment`; tests/Catalogue.php merges every file's rows by kind.

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
        PutProductionOrderAttachment::class => [
            PutProductionOrderAttachment::class,
            [['AttachmentID' => $id, 'IsProcessed' => true]],
            Method::PUT,
            '/ExternalApi/v2/production/order/attachment',
            [],
            ['AttachmentID' => $id, 'IsProcessed' => true],
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
            Method::POST,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderID' => $id],
            ['FileName' => 'a.txt'],
        ],
        'production order attachment put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->put(['AttachmentID' => $id, 'IsProcessed' => true]),
            PutProductionOrderAttachment::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/attachment',
            [],
            ['AttachmentID' => $id, 'IsProcessed' => true],
        ],
        'production order attachment delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->delete($id),
            DeleteProductionOrderAttachment::class,
            Method::DELETE,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderAttachmentID' => $id],
            null,
        ],
        'production order attachment get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->attachment()->get($id, returnAttachmentsContent: true),
            GetProductionOrderAttachment::class,
            Method::GET,
            '/ExternalApi/v2/production/order/attachment',
            ['ProductionOrderID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
    ],
    'dtos' => [
        PostProductionOrderAttachment::class => [PostProductionOrderAttachment::class, [[], $id], Cin7Payloads::load('production/order/attachment', 'post.response'), ProductionOrderAttachmentData::class, ''],
        PutProductionOrderAttachment::class => [PutProductionOrderAttachment::class, [[]], Cin7Payloads::load('production/order/attachment', 'put.response'), ProductionOrderAttachmentData::class, ''],
        GetProductionOrderAttachment::class => [GetProductionOrderAttachment::class, [$id], Cin7Payloads::load('production/order/attachment', 'get.response'), ProductionOrderAttachmentsData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderAttachmentData production/order/attachment' => [ProductionOrderAttachmentData::class, Cin7Payloads::load('production/order/attachment', 'post.request')],
        'ProductionOrderAttachmentPutData production/order/attachment' => [ProductionOrderAttachmentPutData::class, Cin7Payloads::load('production/order/attachment', 'put.request')],
    ],
];
