<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomOperationData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomPostData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomPutData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomsData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomAttachmentData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomComponentData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomNoteData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomOperationData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomOperationLinkData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomOperationProductData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomResourceData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductionBomVariationComponentData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomPostData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomPutData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomsData;
use Ipsocode\Cin7\Requests\Production\ProductionBom\DeleteProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\DeleteProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\GetProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\GetProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PostProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PostProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PutProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PutProductProductionBom;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `production/productionBOM`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        GetProductProductionBom::class => [
            GetProductProductionBom::class,
            [$id, 'returnAttachmentsContent' => true],
            Method::GET,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        PostProductProductionBom::class => [
            PostProductProductionBom::class,
            [['ProductID' => $id]],
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['ProductID' => $id],
        ],
        PutProductProductionBom::class => [
            PutProductProductionBom::class,
            [['BOMID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        DeleteProductProductionBom::class => [
            DeleteProductProductionBom::class,
            [$id, $id],
            Method::DELETE,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductID' => $id, 'BOMID' => $id],
            null,
        ],
        GetProductFamilyProductionBom::class => [
            GetProductFamilyProductionBom::class,
            [$id, 'returnAttachmentsContent' => true],
            Method::GET,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductFamilyID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        PostProductFamilyProductionBom::class => [
            PostProductFamilyProductionBom::class,
            [['ProductFamilyID' => $id]],
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['ProductFamilyID' => $id],
        ],
        PutProductFamilyProductionBom::class => [
            PutProductFamilyProductionBom::class,
            [['BOMID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        DeleteProductFamilyProductionBom::class => [
            DeleteProductFamilyProductionBom::class,
            [$id, $id],
            Method::DELETE,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductFamilyID' => $id, 'BOMID' => $id],
            null,
        ],
    ],
    'resources' => [
        'production productionBom getProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->getProduct($id, returnAttachmentsContent: true),
            GetProductProductionBom::class,
            Method::GET,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        'production productionBom postProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->postProduct(['ProductID' => $id]),
            PostProductProductionBom::class,
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['ProductID' => $id],
        ],
        'production productionBom putProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->putProduct(['BOMID' => $id]),
            PutProductProductionBom::class,
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        'production productionBom deleteProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->deleteProduct($id, $id),
            DeleteProductProductionBom::class,
            Method::DELETE,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductID' => $id, 'BOMID' => $id],
            null,
        ],
        'production productionBom getProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->getProductFamily($id, returnAttachmentsContent: true),
            GetProductFamilyProductionBom::class,
            Method::GET,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductFamilyID' => $id, 'ReturnAttachmentsContent' => 'true'],
            null,
        ],
        'production productionBom postProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->postProductFamily(['ProductFamilyID' => $id]),
            PostProductFamilyProductionBom::class,
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['ProductFamilyID' => $id],
        ],
        'production productionBom putProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->putProductFamily(['BOMID' => $id]),
            PutProductFamilyProductionBom::class,
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        'production productionBom deleteProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->deleteProductFamily($id, $id),
            DeleteProductFamilyProductionBom::class,
            Method::DELETE,
            '/ExternalApi/v2/production/productionBOM',
            ['ProductFamilyID' => $id, 'BOMID' => $id],
            null,
        ],
    ],
    'dtos' => [
        GetProductProductionBom::class => [GetProductProductionBom::class, [$id], Cin7Payloads::load('production/productionBOM', 'product.get.response'), ProductProductionBomsData::class, ''],
        PostProductProductionBom::class => [PostProductProductionBom::class, [[]], Cin7Payloads::load('production/productionBOM', 'product.post.response'), ProductProductionBomsData::class, ''],
        PutProductProductionBom::class => [PutProductProductionBom::class, [[]], Cin7Payloads::load('production/productionBOM', 'product.put.response'), ProductProductionBomsData::class, ''],
        GetProductFamilyProductionBom::class => [GetProductFamilyProductionBom::class, [$id], Cin7Payloads::load('production/productionBOM', 'family.get.response'), ProductFamilyProductionBomsData::class, ''],
        PostProductFamilyProductionBom::class => [PostProductFamilyProductionBom::class, [[]], Cin7Payloads::load('production/productionBOM', 'family.post.response'), ProductFamilyProductionBomsData::class, ''],
        PutProductFamilyProductionBom::class => [PutProductFamilyProductionBom::class, [[]], Cin7Payloads::load('production/productionBOM', 'family.put.response'), ProductFamilyProductionBomsData::class, ''],
    ],
    'bodies' => [
        'ProductProductionBomPostData production/productionBOM' => [ProductProductionBomPostData::class, Cin7Payloads::load('production/productionBOM', 'product.post.request')],
        'ProductProductionBomPutData production/productionBOM' => [ProductProductionBomPutData::class, Cin7Payloads::load('production/productionBOM', 'product.put.request')],
        'ProductFamilyProductionBomPostData production/productionBOM' => [ProductFamilyProductionBomPostData::class, Cin7Payloads::load('production/productionBOM', 'family.post.request')],
        'ProductFamilyProductionBomPutData production/productionBOM' => [ProductFamilyProductionBomPutData::class, Cin7Payloads::load('production/productionBOM', 'family.put.request')],
    ],
    'missing' => [
        'ProductionBomResourceData without ResourceID' => [ProductionBomResourceData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Resources'][0], 'ResourceID')],
        'ProductionBomResourceData without Quantity' => [ProductionBomResourceData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Resources'][0], 'Quantity')],
        'ProductionBomResourceData without Position' => [ProductionBomResourceData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Resources'][0], 'Position')],
        'ProductionBomResourceData without CostCalculationType' => [ProductionBomResourceData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Resources'][0], 'CostCalculationType')],
        'ProductionBomComponentData without Quantity' => [ProductionBomComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Components'][0], 'Quantity')],
        'ProductionBomComponentData without Position' => [ProductionBomComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Components'][0], 'Position')],
        'ProductionBomAttachmentData without Position' => [ProductionBomAttachmentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Attachments'][0], 'Position')],
        'ProductionBomAttachmentData without ContentType' => [ProductionBomAttachmentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0]['Attachments'][0], 'ContentType')],
        'ProductionBomNoteData without Position' => [ProductionBomNoteData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.get.response')['ProductionBOMs'][0]['Operations'][0]['Notes'][0], 'Position')],
        'ProductionBomOperationLinkData without RelationType' => [ProductionBomOperationLinkData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.get.response')['ProductionBOMs'][0]['Operations'][0]['OperationLinks'][0], 'RelationType')],
        'ProductionBomOperationProductData without CostCalculationType' => [ProductionBomOperationProductData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.get.response')['ProductionBOMs'][0]['Operations'][0]['OutputProducts'][0], 'CostCalculationType')],
        'ProductionBomOperationProductData without OutputQuantity' => [ProductionBomOperationProductData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.get.response')['ProductionBOMs'][0]['Operations'][0]['OutputProducts'][0], 'OutputQuantity')],
        'ProductionBomOperationProductData without Position' => [ProductionBomOperationProductData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.get.response')['ProductionBOMs'][0]['Operations'][0]['OutputProducts'][0], 'Position')],
        'ProductionBomVariationComponentData without QuantitySettingsJSON' => [ProductionBomVariationComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.get.response')['ProductionBOMs'][0]['Operations'][0]['VariationComponents'][0], 'QuantitySettingsJSON')],
        'ProductionBomVariationComponentData without MapVariationsJSON' => [ProductionBomVariationComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.get.response')['ProductionBOMs'][0]['Operations'][0]['VariationComponents'][0], 'MapVariationsJSON')],
        'ProductionBomVariationComponentData without QuantityOptionName' => [ProductionBomVariationComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.get.response')['ProductionBOMs'][0]['Operations'][0]['VariationComponents'][0], 'QuantityOptionName')],
        'ProductionBomVariationComponentData without Position' => [ProductionBomVariationComponentData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.get.response')['ProductionBOMs'][0]['Operations'][0]['VariationComponents'][0], 'Position')],
        'ProductionBomOperationData without Order' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'Order')],
        'ProductionBomOperationData without Name' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'Name')],
        'ProductionBomOperationData without CycleTime' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'CycleTime')],
        'ProductionBomOperationData without UnitsPerCycle' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'UnitsPerCycle')],
        'ProductionBomOperationData without WorkCenterID' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'WorkCenterID')],
        'ProductionBomOperationData without OperationType' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'OperationType')],
        'ProductionBomOperationData without IsDropShip' => [ProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0]['Operations'][0], 'IsDropShip')],
        'ProductFamilyProductionBomOperationData without Order' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'Order')],
        'ProductFamilyProductionBomOperationData without Name' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'Name')],
        'ProductFamilyProductionBomOperationData without CycleTime' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'CycleTime')],
        'ProductFamilyProductionBomOperationData without UnitsPerCycle' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'UnitsPerCycle')],
        'ProductFamilyProductionBomOperationData without WorkCenterID' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'WorkCenterID')],
        'ProductFamilyProductionBomOperationData without OperationType' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'OperationType')],
        'ProductFamilyProductionBomOperationData without IsDropShip' => [ProductFamilyProductionBomOperationData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0]['Operations'][0], 'IsDropShip')],
        'ProductionBomData without OutputQuantity' => [ProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0], 'OutputQuantity')],
        'ProductionBomData without BufferPercent' => [ProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0], 'BufferPercent')],
        'ProductionBomData without Version' => [ProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0], 'Version')],
        'ProductionBomData without Name' => [ProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0], 'Name')],
        'ProductionBomData without IsDefault' => [ProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request')['ProductionBOMs'][0], 'IsDefault')],
        'ProductFamilyProductionBomData without OutputQuantity' => [ProductFamilyProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0], 'OutputQuantity')],
        'ProductFamilyProductionBomData without BufferPercent' => [ProductFamilyProductionBomData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request')['ProductionBOMs'][0], 'BufferPercent')],
        'ProductProductionBomPostData without ProductID' => [ProductProductionBomPostData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request'), 'ProductID')],
        'ProductProductionBomPostData without ProductionBOMs' => [ProductProductionBomPostData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.post.request'), 'ProductionBOMs')],
        'ProductFamilyProductionBomPostData without ProductFamilyID' => [ProductFamilyProductionBomPostData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request'), 'ProductFamilyID')],
        'ProductFamilyProductionBomPostData without ProductionBOMs' => [ProductFamilyProductionBomPostData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.post.request'), 'ProductionBOMs')],
        'ProductProductionBomPutData without BOMID' => [ProductProductionBomPutData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.put.request'), 'BOMID')],
        'ProductProductionBomPutData without OutputQuantity' => [ProductProductionBomPutData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.put.request'), 'OutputQuantity')],
        'ProductProductionBomPutData without Version' => [ProductProductionBomPutData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'product.put.request'), 'Version')],
        'ProductFamilyProductionBomPutData without BOMID' => [ProductFamilyProductionBomPutData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.put.request'), 'BOMID')],
        'ProductFamilyProductionBomPutData without OutputQuantity' => [ProductFamilyProductionBomPutData::class, Arr::except(Cin7Payloads::load('production/productionBOM', 'family.put.request'), 'OutputQuantity')],
    ],
    'required' => [
        ProductionBomResourceData::class => ['ResourceID', 'Quantity', 'Position', 'CostCalculationType'],
        ProductionBomComponentData::class => ['Quantity', 'Position'],
        ProductionBomAttachmentData::class => ['Position', 'ContentType'],
        ProductionBomNoteData::class => ['Position'],
        ProductionBomOperationLinkData::class => ['RelationType'],
        ProductionBomOperationProductData::class => ['CostCalculationType', 'OutputQuantity', 'Position'],
        ProductionBomVariationComponentData::class => ['QuantitySettingsJSON', 'MapVariationsJSON', 'QuantityOptionName', 'Position'],
        ProductionBomOperationData::class => ['Order', 'Name', 'CycleTime', 'UnitsPerCycle', 'WorkCenterID', 'OperationType', 'IsDropShip'],
        ProductFamilyProductionBomOperationData::class => ['Order', 'Name', 'CycleTime', 'UnitsPerCycle', 'WorkCenterID', 'OperationType', 'IsDropShip'],
        ProductionBomData::class => ['OutputQuantity', 'BufferPercent', 'Version', 'Name', 'IsDefault'],
        ProductFamilyProductionBomData::class => ['OutputQuantity', 'BufferPercent'],
        ProductProductionBomPostData::class => ['ProductID', 'ProductionBOMs'],
        ProductFamilyProductionBomPostData::class => ['ProductFamilyID', 'ProductionBOMs'],
        ProductProductionBomPutData::class => ['BOMID', 'OutputQuantity', 'Version'],
        ProductFamilyProductionBomPutData::class => ['OutputQuantity', 'BOMID'],
    ],
];
