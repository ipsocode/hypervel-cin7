<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunCompletePostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunManualJournalData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunManualJournalsPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationAttachmentData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationCompletePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationNoteData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationResourceCostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationResumePutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationStartPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOperationSuspendPutData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunOutputData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunPostData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunTraceabilityData;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\Production\Order\Run\GetProductionOrderRun;
use Ipsocode\Cin7\Requests\Production\Order\Run\PostProductionOrderRun;
use Ipsocode\Cin7\Requests\Production\Order\Run\PutProductionOrderRun;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/order/run`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

// The reference's run examples leave the lists of an operation and of a run empty, so this one fills them.
$full = Cin7Payloads::load('production/order/run', 'get.response');
$product = ['ProductID' => $id, 'ProductSKU' => 'Bread', 'ProductName' => 'Baked Bread', 'Unit' => 'Item', 'OutputQuantity' => 1.0, 'ExpectedQuantity' => 1.0, 'WastageQuantity' => 0.0, 'BatchSN' => 'B1', 'ExpiryDate' => '2024-05-01T00:00:00', 'LocationID' => $id, 'LocationName' => 'Main Warehouse'];
$full['Runs'][0]['Operations'][0] = [
    ...$full['Runs'][0]['Operations'][0],
    'InputProducts' => [$product],
    'OutputProducts' => [$product],
    'FinishedProducts' => [$product],
    'CoManTasks' => [['TaskID' => $id, 'Type' => 'PurchaseTask', 'TaskStatus' => 'DRAFT', 'TaskNumber' => 'PO-00001']],
    'CoManTaskLines' => [['TaskID' => $id, 'ProductID' => $id, 'ProductCode' => 'Bread', 'ProductName' => 'Baked Bread', 'Unit' => 'Item', 'Quantity' => 1.0, 'Cost' => 5.0, 'BatchSN' => 'B1', 'ExpiryDate' => '2024-05-01T00:00:00', 'LocationID' => $id, 'LocationName' => 'Main Warehouse', 'ReceivedDate' => '2024-05-02T00:00:00']],
    'Notes' => [['NoteID' => $id, 'Note' => 'Mind the oven', 'Position' => 1]],
];
$full['Runs'][0]['PendingOutput'] = [['ProductID' => $id, 'ProductCode' => 'Bread', 'ProductName' => 'Baked Bread', 'Unit' => 'Item', 'CostingMethod' => 'FEBATCH', 'BatchSN' => 'B1', 'ExpiryDate' => '2024-05-01T00:00:00']];

return [
    'requests' => [
        PostProductionOrderRun::class => [
            PostProductionOrderRun::class,
            [['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => true]],
            Method::POST,
            '/ExternalApi/v2/production/order/run',
            [],
            ['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => true],
        ],
        GetProductionOrderRun::class => [
            GetProductionOrderRun::class,
            [$id, 'includeAttachmentContent' => true],
            Method::GET,
            '/ExternalApi/v2/production/order/run',
            ['ProductionOrderID' => $id, 'IncludeAttachmentContent' => 'true'],
            null,
        ],
        PutProductionOrderRun::class => [
            PutProductionOrderRun::class,
            [['RunID' => $id, 'Quantity' => 15], 'productionOrderId' => $id, 'increaseOrderQuantity' => true],
            Method::PUT,
            '/ExternalApi/v2/production/order/run',
            ['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => 'true'],
            ['RunID' => $id, 'Quantity' => 15],
        ],
    ],
    'resources' => [
        'production order run post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->post(['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => true]),
            PostProductionOrderRun::class,
            Method::POST,
            '/ExternalApi/v2/production/order/run',
            [],
            ['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => true],
        ],
        'production order run get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->get($id, includeAttachmentContent: true),
            GetProductionOrderRun::class,
            Method::GET,
            '/ExternalApi/v2/production/order/run',
            ['ProductionOrderID' => $id, 'IncludeAttachmentContent' => 'true'],
            null,
        ],
        'production order run put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->run()->put(['RunID' => $id, 'Quantity' => 15], $id, true),
            PutProductionOrderRun::class,
            Method::PUT,
            '/ExternalApi/v2/production/order/run',
            ['ProductionOrderID' => $id, 'IncreaseOrderQuantity' => 'true'],
            ['RunID' => $id, 'Quantity' => 15],
        ],
    ],
    'dtos' => [
        PostProductionOrderRun::class => [PostProductionOrderRun::class, [[]], Cin7Payloads::load('production/order/run', 'post.response'), ProductionRunsData::class, ''],
        GetProductionOrderRun::class => [GetProductionOrderRun::class, [$id], Cin7Payloads::load('production/order/run', 'get.response'), ProductionRunsData::class, ''],
        PutProductionOrderRun::class => [PutProductionOrderRun::class, [[], $id, true], Cin7Payloads::load('production/order/run', 'put.response'), ProductionRunData::class, ''],
        'GetProductionOrderRun with every list filled' => [GetProductionOrderRun::class, [$id], $full, ProductionRunsData::class, ''],
    ],
    'bodies' => [
        'ProductionRunPostData production/order/run' => [ProductionRunPostData::class, Cin7Payloads::load('production/order/run', 'post.request')],
        'ProductionRunData production/order/run' => [ProductionRunData::class, Cin7Payloads::load('production/order/run', 'put.request')],
    ],
    'missing' => [
        'ProductionRunOperationResourceCostData without RunCostID' => [ProductionRunOperationResourceCostData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.response')['Runs'][0]['Operations'][0]['ResourceCosts'][0], 'RunCostID')],
        'ProductionRunOperationResourceCostData without ProductID' => [ProductionRunOperationResourceCostData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.response')['Runs'][0]['Operations'][0]['ResourceCosts'][0], 'ProductID')],
        'ProductionRunOperationResourceCostData without Cost' => [ProductionRunOperationResourceCostData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.response')['Runs'][0]['Operations'][0]['ResourceCosts'][0], 'Cost')],
        'ProductionRunOperationAttachmentData without Position' => [ProductionRunOperationAttachmentData::class, Arr::except(Cin7Payloads::load('production/order/run', 'put.response')['Operations'][0]['Attachments'][0], 'Position')],
        'ProductionRunOutputData without Quantity' => [ProductionRunOutputData::class, Arr::except(Cin7Payloads::load('production/order/run/complete', 'put.request')['FinishedProducts'][0], 'Quantity')],
        'ProductionRunOutputData without WastageQuantity' => [ProductionRunOutputData::class, Arr::except(Cin7Payloads::load('production/order/run/complete', 'put.request')['FinishedProducts'][0], 'WastageQuantity')],
        'ProductionRunOutputData without ReceivedDate' => [ProductionRunOutputData::class, Arr::except(Cin7Payloads::load('production/order/run/complete', 'put.request')['FinishedProducts'][0], 'ReceivedDate')],
        'ProductionRunManualJournalData without Amount' => [ProductionRunManualJournalData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request')['ManualJournals'][0], 'Amount')],
        'ProductionRunManualJournalData without Date' => [ProductionRunManualJournalData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request')['ManualJournals'][0], 'Date')],
        'ProductionRunManualJournalData without Debit' => [ProductionRunManualJournalData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request')['ManualJournals'][0], 'Debit')],
        'ProductionRunManualJournalData without Credit' => [ProductionRunManualJournalData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request')['ManualJournals'][0], 'Credit')],
        'ProductionRunTraceabilityData without ProducedProductID' => [ProductionRunTraceabilityData::class, Arr::except(Cin7Payloads::load('production/order/run', 'get.response')['Runs'][0]['Traceability'][0], 'ProducedProductID')],
        'ProductionRunTraceabilityData without ProducedProductBatchSN' => [ProductionRunTraceabilityData::class, Arr::except(Cin7Payloads::load('production/order/run', 'get.response')['Runs'][0]['Traceability'][0], 'ProducedProductBatchSN')],
        'ProductionRunTraceabilityData without UsedProductID' => [ProductionRunTraceabilityData::class, Arr::except(Cin7Payloads::load('production/order/run', 'get.response')['Runs'][0]['Traceability'][0], 'UsedProductID')],
        'ProductionRunTraceabilityData without UsedProductBatchSN' => [ProductionRunTraceabilityData::class, Arr::except(Cin7Payloads::load('production/order/run', 'get.response')['Runs'][0]['Traceability'][0], 'UsedProductBatchSN')],
        'ProductionRunOperationData without OperationID' => [ProductionRunOperationData::class, Arr::except(Cin7Payloads::load('production/order/run', 'get.response')['Runs'][0]['Operations'][0], 'OperationID')],
        'ProductionRunPostData without ProductionOrderID' => [ProductionRunPostData::class, Arr::except(Cin7Payloads::load('production/order/run', 'post.request'), 'ProductionOrderID')],
        'ProductionRunPostData without Runs' => [ProductionRunPostData::class, Arr::except(Cin7Payloads::load('production/order/run', 'post.request'), 'Runs')],
        'ProductionRunCompletePostData without ProductionOrderID' => [ProductionRunCompletePostData::class, Arr::except(Cin7Payloads::load('production/order/run/complete', 'put.request'), 'ProductionOrderID')],
        'ProductionRunCompletePostData without ProductionRunID' => [ProductionRunCompletePostData::class, Arr::except(Cin7Payloads::load('production/order/run/complete', 'put.request'), 'ProductionRunID')],
        'ProductionRunUndoData without ProductionOrderID' => [ProductionRunUndoData::class, Arr::except(Cin7Payloads::load('production/order/run/undo', 'put.request'), 'ProductionOrderID')],
        'ProductionRunUndoData without ProductionRunID' => [ProductionRunUndoData::class, Arr::except(Cin7Payloads::load('production/order/run/undo', 'put.request'), 'ProductionRunID')],
        'ProductionRunManualJournalsPutData without RunID' => [ProductionRunManualJournalsPutData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request'), 'RunID')],
        'ProductionRunManualJournalsPutData without ManualJournals' => [ProductionRunManualJournalsPutData::class, Arr::except(Cin7Payloads::load('production/order/run/manualJournal', 'put.request'), 'ManualJournals')],
        'ProductionRunOperationStartPutData without ProductionOrderID' => [ProductionRunOperationStartPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/start', 'put.request'), 'ProductionOrderID')],
        'ProductionRunOperationStartPutData without ProductionRunID' => [ProductionRunOperationStartPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/start', 'put.request'), 'ProductionRunID')],
        'ProductionRunOperationStartPutData without RunOperationID' => [ProductionRunOperationStartPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/start', 'put.request'), 'RunOperationID')],
        'ProductionRunOperationSuspendPutData without ProductionOrderID' => [ProductionRunOperationSuspendPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/suspend', 'put.request'), 'ProductionOrderID')],
        'ProductionRunOperationSuspendPutData without ProductionRunID' => [ProductionRunOperationSuspendPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/suspend', 'put.request'), 'ProductionRunID')],
        'ProductionRunOperationSuspendPutData without RunOperationID' => [ProductionRunOperationSuspendPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/suspend', 'put.request'), 'RunOperationID')],
        'ProductionRunOperationSuspendPutData without SuspendReasonID' => [ProductionRunOperationSuspendPutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/suspend', 'put.request'), 'SuspendReasonID')],
        'ProductionRunOperationResumePutData without ProductionOrderID' => [ProductionRunOperationResumePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/resume', 'put.request'), 'ProductionOrderID')],
        'ProductionRunOperationResumePutData without ProductionRunID' => [ProductionRunOperationResumePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/resume', 'put.request'), 'ProductionRunID')],
        'ProductionRunOperationResumePutData without RunOperationID' => [ProductionRunOperationResumePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/resume', 'put.request'), 'RunOperationID')],
        'ProductionRunOperationCompletePutData without ProductionOrderID' => [ProductionRunOperationCompletePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.request'), 'ProductionOrderID')],
        'ProductionRunOperationCompletePutData without ProductionRunID' => [ProductionRunOperationCompletePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.request'), 'ProductionRunID')],
        'ProductionRunOperationCompletePutData without RunOperationID' => [ProductionRunOperationCompletePutData::class, Arr::except(Cin7Payloads::load('production/order/run/operation/complete', 'put.request'), 'RunOperationID')],
    ],
    'required' => [
        ProductionRunOperationResourceCostData::class => ['RunCostID', 'ProductID', 'Cost'],
        ProductionRunOperationAttachmentData::class => ['Position'],
        ProductionRunOperationNoteData::class => ['Position'],
        ProductionRunOutputData::class => ['Quantity', 'WastageQuantity', 'ReceivedDate'],
        ProductionRunManualJournalData::class => ['Amount', 'Date', 'Debit', 'Credit'],
        ProductionRunTraceabilityData::class => ['ProducedProductID', 'ProducedProductBatchSN', 'UsedProductID', 'UsedProductBatchSN'],
        ProductionRunOperationData::class => ['OperationID'],
        ProductionRunPostData::class => ['ProductionOrderID', 'Runs'],
        ProductionRunCompletePostData::class => ['ProductionOrderID', 'ProductionRunID'],
        ProductionRunUndoData::class => ['ProductionOrderID', 'ProductionRunID'],
        ProductionRunManualJournalsPutData::class => ['RunID', 'ManualJournals'],
        ProductionRunOperationStartPutData::class => ['ProductionOrderID', 'ProductionRunID', 'RunOperationID'],
        ProductionRunOperationSuspendPutData::class => ['ProductionOrderID', 'ProductionRunID', 'RunOperationID', 'SuspendReasonID'],
        ProductionRunOperationResumePutData::class => ['ProductionOrderID', 'ProductionRunID', 'RunOperationID'],
        ProductionRunOperationCompletePutData::class => ['ProductionOrderID', 'ProductionRunID', 'RunOperationID'],
    ],
];
