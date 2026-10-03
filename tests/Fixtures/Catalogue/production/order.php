<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentPutData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAuthorisePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderComponentData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderOperationAttachmentData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderOperationData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderOperationNoteData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderPostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderPutData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderReleasePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderResourceData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderUndoPostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderVoidPostData;
use Ipsocode\Cin7\Requests\Production\Order\GetProductionOrder;
use Ipsocode\Cin7\Requests\Production\Order\PostProductionOrder;
use Ipsocode\Cin7\Requests\Production\Order\PutProductionOrder;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductionOrder::class => [
            GetProductionOrder::class,
            [$id, 'returnAttachmentsContent' => true],
            Method::GET,
            '/ExternalApi/v2/production/order',
            ['ProductionOrderID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        PostProductionOrder::class => [
            PostProductionOrder::class,
            [['ProductID' => $id, 'LocationID' => $id], 'recalculateDates' => true],
            Method::POST,
            '/ExternalApi/v2/production/order',
            ['RecalculateDates' => 'true'],
            ['ProductID' => $id, 'LocationID' => $id],
        ],
        PutProductionOrder::class => [
            PutProductionOrder::class,
            [['ProductionOrderID' => $id, 'Quantity' => 5], 'allowRecalculateDates' => true, 'allowRecalculateCyclesAndQuantities' => false],
            Method::PUT,
            '/ExternalApi/v2/production/order',
            ['AllowRecalculateDates' => 'true', 'AllowRecalculateCyclesAndQuantities' => 'false'],
            ['ProductionOrderID' => $id, 'Quantity' => 5],
        ],
    ],
    'resources' => [
        'production order get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->get($id, returnAttachmentsContent: true),
            GetProductionOrder::class,
            Method::GET,
            '/ExternalApi/v2/production/order',
            ['ProductionOrderID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        'production order post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->post(['ProductID' => $id, 'LocationID' => $id], recalculateDates: true),
            PostProductionOrder::class,
            Method::POST,
            '/ExternalApi/v2/production/order',
            ['RecalculateDates' => 'true'],
            ['ProductID' => $id, 'LocationID' => $id],
        ],
        'production order put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->put(['ProductionOrderID' => $id, 'Quantity' => 5], allowRecalculateDates: true, allowRecalculateCyclesAndQuantities: false),
            PutProductionOrder::class,
            Method::PUT,
            '/ExternalApi/v2/production/order',
            ['AllowRecalculateDates' => 'true', 'AllowRecalculateCyclesAndQuantities' => 'false'],
            ['ProductionOrderID' => $id, 'Quantity' => 5],
        ],
    ],
    'dtos' => [
        GetProductionOrder::class => [GetProductionOrder::class, [$id], Cin7Payloads::load('production/order', 'get.response'), ProductionOrdersData::class, ''],
        PostProductionOrder::class => [PostProductionOrder::class, [[]], Cin7Payloads::load('production/order', 'post.response'), ProductionOrdersData::class, ''],
        PutProductionOrder::class => [PutProductionOrder::class, [[]], Cin7Payloads::load('production/order', 'put.response'), ProductionOrdersData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderPostData production/order' => [ProductionOrderPostData::class, Cin7Payloads::load('production/order', 'post.request')],
        'ProductionOrderPutData production/order' => [ProductionOrderPutData::class, Cin7Payloads::load('production/order', 'put.request')],
    ],
    'missing' => [
        'ProductionOrderOperationAttachmentData without ContentType' => [ProductionOrderOperationAttachmentData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Attachments'][0], 'ContentType')],
        'ProductionOrderOperationAttachmentData without Position' => [ProductionOrderOperationAttachmentData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Attachments'][0], 'Position')],
        'ProductionOrderComponentData without Position' => [ProductionOrderComponentData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Components'][0], 'Position')],
        'ProductionOrderComponentData without Quantity' => [ProductionOrderComponentData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Components'][0], 'Quantity')],
        'ProductionOrderOperationNoteData without Position' => [ProductionOrderOperationNoteData::class, Arr::except(Cin7Payloads::load('production/order/authorise', 'post.response')['ProductionOrders'][0]['Operations'][0]['Notes'][0], 'Position')],
        'ProductionOrderResourceData without Position' => [ProductionOrderResourceData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Resources'][0], 'Position')],
        'ProductionOrderResourceData without Quantity' => [ProductionOrderResourceData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Resources'][0], 'Quantity')],
        'ProductionOrderResourceData without CostCalculationType' => [ProductionOrderResourceData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0]['Resources'][0], 'CostCalculationType')],
        'ProductionOrderOperationData without Order' => [ProductionOrderOperationData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0], 'Order')],
        'ProductionOrderOperationData without Name' => [ProductionOrderOperationData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0], 'Name')],
        'ProductionOrderOperationData without CycleTime' => [ProductionOrderOperationData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0], 'CycleTime')],
        'ProductionOrderOperationData without UnitsPerCycle' => [ProductionOrderOperationData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0], 'UnitsPerCycle')],
        'ProductionOrderOperationData without TotalCycleTime' => [ProductionOrderOperationData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0]['Operations'][0], 'TotalCycleTime')],
        'ProductionOrderPostData without ProductID' => [ProductionOrderPostData::class, Arr::except(Cin7Payloads::load('production/order', 'post.request'), 'ProductID')],
        'ProductionOrderPostData without LocationID' => [ProductionOrderPostData::class, Arr::except(Cin7Payloads::load('production/order', 'post.request'), 'LocationID')],
        'ProductionOrderData without ProductionOrderID' => [ProductionOrderData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0], 'ProductionOrderID')],
        'ProductionOrderData without ProductID' => [ProductionOrderData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0], 'ProductID')],
        'ProductionOrderData without LocationID' => [ProductionOrderData::class, Arr::except(Cin7Payloads::load('production/order', 'get.response')['ProductionOrders'][0], 'LocationID')],
        'ProductionOrderPutData without ProductionOrderID' => [ProductionOrderPutData::class, Arr::except(Cin7Payloads::load('production/order', 'put.request'), 'ProductionOrderID')],
        'ProductionOrderPutData without LocationID' => [ProductionOrderPutData::class, Arr::except(Cin7Payloads::load('production/order', 'put.request'), 'LocationID')],
        'ProductionOrderAuthorisePostData without productionOrderID' => [ProductionOrderAuthorisePostData::class, Arr::except(Cin7Payloads::load('production/order/authorise', 'post.request'), 'productionOrderID')],
        'ProductionOrderReleasePostData without productionOrderID' => [ProductionOrderReleasePostData::class, Arr::except(Cin7Payloads::load('production/order/release', 'post.request'), 'productionOrderID')],
        'ProductionOrderUndoPostData without productionOrderID' => [ProductionOrderUndoPostData::class, Arr::except(Cin7Payloads::load('production/order/undo', 'post.request'), 'productionOrderID')],
        'ProductionOrderVoidPostData without productionOrderID' => [ProductionOrderVoidPostData::class, Arr::except(Cin7Payloads::load('production/order/void', 'post.request'), 'productionOrderID')],
        'ProductionOrderAttachmentPutData without AttachmentID' => [ProductionOrderAttachmentPutData::class, Arr::except(Cin7Payloads::load('production/order/attachment', 'put.request'), 'AttachmentID')],
    ],
    'required' => [
        ProductionOrderOperationAttachmentData::class => ['ContentType', 'Position'],
        ProductionOrderComponentData::class => ['Position', 'Quantity'],
        ProductionOrderOperationNoteData::class => ['Position'],
        ProductionOrderResourceData::class => ['Position', 'Quantity', 'CostCalculationType'],
        ProductionOrderOperationData::class => ['Order', 'Name', 'CycleTime', 'UnitsPerCycle', 'TotalCycleTime'],
        ProductionOrderPostData::class => ['LocationID', 'ProductID'],
        ProductionOrderData::class => ['LocationID', 'ProductionOrderID', 'ProductID'],
        ProductionOrderPutData::class => ['LocationID', 'ProductionOrderID'],
        ProductionOrderAuthorisePostData::class => ['productionOrderID'],
        ProductionOrderReleasePostData::class => ['productionOrderID'],
        ProductionOrderUndoPostData::class => ['productionOrderID'],
        ProductionOrderVoidPostData::class => ['productionOrderID'],
        ProductionOrderAttachmentPutData::class => ['AttachmentID'],
    ],
];
