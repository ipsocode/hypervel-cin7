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

// The catalog rows for `production/productionBOM`; tests/Catalogue.php merges every file's rows by kind.

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
        PostProductProductionBom::class . ' with data' => [
            PostProductProductionBom::class,
            [static function (): ProductProductionBomPostData {
                $fixture = Cin7Payloads::load('production/productionBOM', 'product.post.request');
                Arr::set($fixture, 'ProductionBOMs.0.Operations.1.OperationLinks.0.RelatedOperationID', '2d4f6c0a-7a1e-4f43-9d5e-1c4f0e3d9a11');

                return ProductProductionBomPostData::from($fixture);
            }],
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            [
                'ProductID' => 'b8cb2705-fdfb-48f0-99a4-419e11d63053',
                'ProductionBOMs' => [
                    [
                        'OutputQuantity' => 1.0,
                        'BufferPercent' => 0.0,
                        'Version' => 3,
                        'Name' => '',
                        'IsDefault' => true,
                        'BOMID' => '',
                        'ProductID' => '',
                        'InstructionUrl' => '',
                        'IgnoreCumulativeLeadTime' => false,
                        'ComponentProductionLeadTime' => 0,
                        'IsTracing' => true,
                        'IssueMethod' => 1,
                        'IssueMethodComponent' => 2,
                        'IssueMethodParameter' => 1,
                        'MinQuantity' => 50.0,
                        'MaxQuantity' => 100.0,
                        'DeviationPercent' => 5.0,
                        'RunSize' => 10.0,
                        'CreatedDate' => '2021-01-29T00:00:00',
                        'CreatedBy' => '',
                        'DeliveryTo' => '',
                        'Operations' => [
                            [
                                'Order' => 1,
                                'Name' => 'Operation 1',
                                'CycleTime' => 3600,
                                'UnitsPerCycle' => 1.0,
                                'WorkCenterID' => 'a11ac85f-e4ef-487e-8196-9cc0dc954697',
                                'OperationType' => 1,
                                'IsDropShip' => false,
                                'OperationID' => '',
                                'Resources' => [
                                    [
                                        'ResourceID' => 'c49cd399-2904-4868-98ad-14449da5b2d7',
                                        'Quantity' => 1.0,
                                        'Position' => 1,
                                        'CostCalculationType' => 'CostPerUnitTime',
                                        'BOMResourceID' => '',
                                        'ResourceName' => 'Cook',
                                        'ResourceCode' => 'Cook',
                                        'Cost' => 20.0,
                                        'CycleTime' => 3600,
                                    ],
                                ],
                                'Components' => [
                                    [
                                        'Quantity' => 2.0,
                                        'Position' => 2,
                                        'BOMComponentID' => '',
                                        'ProductID' => '86875aba-9143-435e-b5f7-b036b1873dcf',
                                        'ProductSKU' => 'Eggs',
                                        'ProductName' => 'Eggs',
                                        'WastageQty' => 0.0,
                                        'WastagePercent' => 0.0,
                                        'Cost' => 0.3864,
                                        'CostingMethod' => 'FIFO',
                                        'Unit' => 'Item',
                                        'IsBackflush' => false,
                                    ],
                                ],
                                'Attachments' => [
                                    [
                                        'Position' => 3,
                                        'ContentType' => 'application/octet-stream',
                                        'FileName' => 'AudMade Update Sheet - IMPORTED DONE v2 (1).csv',
                                        'Content' => 'QWN0aW9uLFByb2R1Y3RTS1UsUHJvZHVjdE5hbWUsUXVhbnRpdHlUb1Byb',
                                    ],
                                ],
                                'Notes' => [],
                                'OperationLinks' => [],
                                'InputProducts' => [],
                                'OutputProducts' => [],
                                'FinishedProducts' => [],
                                'IsBackflush' => false,
                                'CycleTimeString' => '1h',
                                'TotalCost' => 20.7728,
                            ],
                            [
                                'Order' => 1,
                                'Name' => 'Operation 2',
                                'CycleTime' => 3600,
                                'UnitsPerCycle' => 1.0,
                                'WorkCenterID' => 'a11ac85f-e4ef-487e-8196-9cc0dc954697',
                                'OperationType' => 1,
                                'IsDropShip' => false,
                                'OperationID' => '',
                                'Resources' => [
                                    [
                                        'ResourceID' => 'c49cd399-2904-4868-98ad-14449da5b2d7',
                                        'Quantity' => 1.0,
                                        'Position' => 1,
                                        'CostCalculationType' => 'CostPerUnitTime',
                                        'BOMResourceID' => '',
                                        'ResourceName' => 'Cook',
                                        'ResourceCode' => 'Cook',
                                        'Cost' => 20.0,
                                        'CycleTime' => 3600,
                                    ],
                                ],
                                'Attachments' => [
                                    [
                                        'Position' => 3,
                                        'ContentType' => 'application/octet-stream',
                                        'FileName' => 'AudMade Update Sheet - IMPORTED DONE v2 (1).csv',
                                        'Content' => 'QWN0aW9uLFByb2R1Y3RTS1UsUHJvZHVjdE5hbWUsUXVhbnRpdHlUb1Byb',
                                    ],
                                ],
                                'Notes' => [],
                                'OperationLinks' => [
                                    [
                                        'RelationType' => 1,
                                        'RelatedOperationID' => '2d4f6c0a-7a1e-4f43-9d5e-1c4f0e3d9a11',
                                        'RelatedOperationName' => 'Operation 1',
                                    ],
                                ],
                                'InputProducts' => [],
                                'OutputProducts' => [],
                                'FinishedProducts' => [],
                                'IsBackflush' => false,
                                'CycleTimeString' => '1h',
                                'TotalCost' => 20.7728,
                            ],
                        ],
                    ],
                ],
                'SetParentHasProductionBom' => true,
            ],
        ],
        PutProductProductionBom::class => [
            PutProductProductionBom::class,
            [['BOMID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        PutProductProductionBom::class . ' with data' => [
            PutProductProductionBom::class,
            [fn (): ProductProductionBomPutData => ProductProductionBomPutData::from(Cin7Payloads::load('production/productionBOM', 'product.put.request'))],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            [
                'BOMID' => 'ac25f767-fbbf-4a56-98d9-648ef1bfe478',
                'OutputQuantity' => 10.0,
                'Version' => 12,
                'ProductID' => 'b8cb2705-fdfb-48f0-99a4-419e11d63053',
                'IssueMethodComponent' => 2,
                'IssueMethodParameter' => 1,
                'Operations' => [
                    [
                        'Order' => 1,
                        'Name' => 'Operation 1++',
                        'CycleTime' => 3600,
                        'UnitsPerCycle' => 1.0,
                        'WorkCenterID' => 'a11ac85f-e4ef-487e-8196-9cc0dc954697',
                        'OperationType' => 'Manufacturing',
                        'IsDropShip' => false,
                        'OperationID' => '8e3154c5-b3b5-49b5-a151-68ae49097b72',
                        'WorkCenterName' => 'Default Workcenter',
                        'Resources' => [
                            [
                                'ResourceID' => 'c49cd399-2904-4868-98ad-14449da5b2d7',
                                'Quantity' => 2.0,
                                'Position' => 1,
                                'CostCalculationType' => 'CostPerUnitTime',
                                'BOMResourceID' => 'e5fdbd02-1422-4a54-a74f-e097397c0c26',
                                'ResourceName' => 'Cook',
                                'ResourceCode' => 'Cook',
                                'CycleTime' => 3600,
                            ],
                        ],
                        'Components' => [
                            [
                                'Quantity' => 3.0,
                                'Position' => 2,
                                'BOMComponentID' => 'a6124c21-b561-4faa-9bce-755869144b2a',
                                'ProductID' => '86875aba-9143-435e-b5f7-b036b1873dcf',
                                'ProductSKU' => 'Eggs',
                                'ProductName' => 'Eggs',
                                'WastageQty' => 0.0,
                                'WastagePercent' => 0.0,
                                'Cost' => 0.3864,
                                'CostingMethod' => 'FIFO',
                                'Unit' => 'Item',
                                'IsBackflush' => false,
                            ],
                        ],
                        'Attachments' => [],
                        'OperationLinks' => [],
                        'IsBackflush' => false,
                        'IsTracing' => false,
                    ],
                ],
            ],
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
        PostProductFamilyProductionBom::class . ' with data' => [
            PostProductFamilyProductionBom::class,
            [static function (): ProductFamilyProductionBomPostData {
                $fixture = Cin7Payloads::load('production/productionBOM', 'family.post.request');
                Arr::set($fixture, 'ProductionBOMs.0.Version', 1);
                Arr::set($fixture, 'ProductionBOMs.0.Name', 'Family BOM');
                Arr::set($fixture, 'ProductionBOMs.0.IsDefault', true);
                Arr::set($fixture, 'ProductionBOMs.0.Operations.0.OperationLinks.0.RelatedOperationName', 'Operation F22');

                return ProductFamilyProductionBomPostData::from($fixture);
            }],
            Method::POST,
            '/ExternalApi/v2/production/productionBOM',
            [],
            [
                'ProductFamilyID' => 'a1688709-6639-4324-aa53-a2120185a7bf',
                'ProductionBOMs' => [
                    [
                        'BufferPercent' => 0.0,
                        'Version' => 1,
                        'Name' => 'Family BOM',
                        'IsDefault' => true,
                        'IssueMethod' => 1,
                        'InstructionUrl' => '',
                        'IgnoreCumulativeLeadTime' => false,
                        'ComponentProductionLeadTime' => 0,
                        'Operations' => [
                            [
                                'Order' => 1,
                                'Name' => 'Operation F22',
                                'CycleTime' => 3600,
                                'UnitsPerCycle' => 1.0,
                                'WorkCenterID' => 'a11ac85f-e4ef-487e-8196-9cc0dc954697',
                                'OperationType' => 1,
                                'IsDropShip' => false,
                                'Resources' => [
                                    [
                                        'ResourceID' => 'c49cd399-2904-4868-98ad-14449da5b2d7',
                                        'Quantity' => 1.0,
                                        'Position' => 1,
                                        'CostCalculationType' => 'CostPerUnitTime',
                                        'ResourceName' => 'Cook',
                                        'ResourceCode' => 'Cook',
                                        'Cost' => 20.0,
                                        'CycleTime' => 3600,
                                    ],
                                ],
                                'Components' => [
                                    [
                                        'Quantity' => 2.0,
                                        'Position' => 2,
                                        'ProductID' => '86875aba-9143-435e-b5f7-b036b1873dcf',
                                        'ProductSKU' => 'Eggs',
                                        'ProductName' => 'Eggs',
                                        'WastageQty' => 0.0,
                                        'WastagePercent' => 0.0,
                                        'Cost' => 0.3864,
                                        'CostingMethod' => 'FIFO',
                                        'Unit' => 'Item',
                                    ],
                                ],
                                'Attachments' => [],
                                'Notes' => [],
                                'OperationLinks' => [
                                    [
                                        'RelationType' => 1,
                                        'RelatedOperationName' => 'Operation F22',
                                    ],
                                ],
                                'InputProducts' => [],
                                'OutputProducts' => [],
                                'FinishedProducts' => [
                                    [
                                        'CostCalculationType' => 'SalesValue',
                                        'OutputQuantity' => 1.0,
                                        'Position' => 4,
                                        'ProductFamilyID' => 'a1688709-6639-4324-aa53-a2120185a7bf',
                                        'ProductSKU' => 'BOM Api Family 1 SKU',
                                        'ProductName' => 'BOM Api Family 1',
                                        'PriceTier' => 1,
                                        'Ratio' => 0.0,
                                        'CostingMethod' => 'FIFO',
                                        'Unit' => 'Item',
                                        'AverageCost' => 0.0,
                                        'WastageCost' => 1.0,
                                    ],
                                ],
                                'CycleTimeString' => '1h',
                                'VariationComponents' => [
                                    [
                                        'QuantitySettingsJSON' => '[{\'OptionKey\':\'Kind 1\',\'OptionValue\':22},{\'OptionKey\':\'Kind 2\',\'OptionValue\':33}]',
                                        'MapVariationsJSON' => '[{\'ProductID\':\'10ece710-1dae-4368-bcee-62912b2682ab\',\'ProductName\':\'Screws Drive Cross \',\'ProductCode\':\'Screws-SKU - 002\',\'Option1SelectedValue\':\'\',\'Option2SelectedValue\':\'\',\'Option3SelectedValue\':\'\',\'IsOption1AllSelected\':false,\'IsOption2AllSelected\':false,\'IsOption3AllSelected\':false,\'QuantitySettingsJSON\':[{\'OptionKey\':\'Kind 1\',\'OptionValue\':null},{\'OptionKey\':\'Kind 2\',\'OptionValue\':35.0}],\'ValidationText\':\'\',\'ValidationState\':\'\'},{\'ProductID\':\'f9888002-5bb7-41bb-8f3d-fc9e611a10c0\',\'ProductName\':\'Screws Phillips Drive Flat\',\'ProductCode\':\'Screws-SKU - 001\',\'Option1SelectedValue\':\'\',\'Option2SelectedValue\':\'\',\'Option3SelectedValue\':\'\',\'IsOption1AllSelected\':false,\'IsOption2AllSelected\':false,\'IsOption3AllSelected\':false,\'QuantitySettingsJSON\':[{\'OptionKey\':\'Kind 1\',\'OptionValue\':22.0},{\'OptionKey\':\'Kind 2\',\'OptionValue\':null}],\'ValidationText\':\'\',\'ValidationState\':\'\'}]',
                                        'QuantityOptionName' => 'Kind',
                                        'Position' => 3,
                                        'ProductFamilySKU' => 'Screws-SKU',
                                        'CostingMethod' => 'FIFO',
                                        'Unit' => 'Item',
                                        'Cost' => 0.3864,
                                    ],
                                ],
                            ],
                        ],
                        'OutputQuantity' => 1.0,
                    ],
                ],
            ],
        ],
        PutProductFamilyProductionBom::class => [
            PutProductFamilyProductionBom::class,
            [['BOMID' => $id]],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            ['BOMID' => $id],
        ],
        PutProductFamilyProductionBom::class . ' with data' => [
            PutProductFamilyProductionBom::class,
            [static function (): ProductFamilyProductionBomPutData {
                $fixture = Cin7Payloads::load('production/productionBOM', 'family.put.request');
                Arr::set($fixture, 'Operations.0.OperationLinks.0.RelatedOperationName', 'Operation ZZZZ');

                return ProductFamilyProductionBomPutData::from($fixture);
            }],
            Method::PUT,
            '/ExternalApi/v2/production/productionBOM',
            [],
            [
                'BOMID' => '9f13328c-de21-45c0-ac29-c8ab29d5e37f',
                'ProductFamilyID' => 'a1688709-6639-4324-aa53-a2120185a7bf',
                'Operations' => [
                    [
                        'Order' => 1,
                        'Name' => 'Operation ZZZZ',
                        'CycleTime' => 3600,
                        'UnitsPerCycle' => 1.0,
                        'WorkCenterID' => 'a11ac85f-e4ef-487e-8196-9cc0dc954697',
                        'OperationType' => 'Manufacturing',
                        'IsDropShip' => false,
                        'OperationID' => '7bde4f66-6604-430b-aea6-db1e824a9be8',
                        'WorkCenterName' => 'Default Workcenter',
                        'Resources' => [
                            [
                                'ResourceID' => 'c49cd399-2904-4868-98ad-14449da5b2d7',
                                'Quantity' => 7.0,
                                'Position' => 1,
                                'CostCalculationType' => 'CostPerUnitTime',
                                'BOMResourceID' => 'c096b60c-bfe0-4d2c-9cbc-50a624fecdd0',
                                'ResourceName' => 'Cook',
                                'ResourceCode' => 'Cook',
                                'Cost' => 20.0,
                                'CycleTime' => 3600,
                            ],
                        ],
                        'Components' => [],
                        'Attachments' => [],
                        'Notes' => [],
                        'OperationLinks' => [
                            [
                                'RelationType' => 1,
                                'ID' => '4f2ef43b-179e-4ca1-831a-12d627d8a25f',
                                'RelatedOperationName' => 'Operation ZZZZ',
                            ],
                        ],
                        'InputProducts' => [],
                        'OutputProducts' => [],
                        'FinishedProducts' => [],
                        'VariationComponents' => [],
                    ],
                ],
                'OutputQuantity' => 15.0,
            ],
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
        ],
        'production productionBom postProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->postProduct(['ProductID' => $id]),
            PostProductProductionBom::class,
        ],
        'production productionBom putProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->putProduct(['BOMID' => $id]),
            PutProductProductionBom::class,
        ],
        'production productionBom deleteProduct' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->deleteProduct($id, $id),
            DeleteProductProductionBom::class,
        ],
        'production productionBom getProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->getProductFamily($id, returnAttachmentsContent: true),
            GetProductFamilyProductionBom::class,
        ],
        'production productionBom postProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->postProductFamily(['ProductFamilyID' => $id]),
            PostProductFamilyProductionBom::class,
        ],
        'production productionBom putProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->putProductFamily(['BOMID' => $id]),
            PutProductFamilyProductionBom::class,
        ],
        'production productionBom deleteProductFamily' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->productionBom()->deleteProductFamily($id, $id),
            DeleteProductFamilyProductionBom::class,
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
        'ProductProductionBomPostData production/productionBOM' => [ProductProductionBomPostData::class, PostProductProductionBom::class, Cin7Payloads::load('production/productionBOM', 'product.post.request')],
        'ProductProductionBomPutData production/productionBOM' => [ProductProductionBomPutData::class, PutProductProductionBom::class, Cin7Payloads::load('production/productionBOM', 'product.put.request')],
        'ProductFamilyProductionBomPostData production/productionBOM' => [ProductFamilyProductionBomPostData::class, PostProductFamilyProductionBom::class, Cin7Payloads::load('production/productionBOM', 'family.post.request')],
        'ProductFamilyProductionBomPutData production/productionBOM' => [ProductFamilyProductionBomPutData::class, PutProductFamilyProductionBom::class, Cin7Payloads::load('production/productionBOM', 'family.put.request')],
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
