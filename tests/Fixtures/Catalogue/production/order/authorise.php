<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAuthorisePostData;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\Production\Order\Authorise\PostProductionOrderAuthorise;
use Workbench\App\Support\Cin7Payloads;

// The catalog rows for `production/order/authorise`; tests/Catalogue.php merges every file's rows by kind.

$id = '38cddb52-9a81-4c07-9791-362936efa552';

return [
    'requests' => [
        PostProductionOrderAuthorise::class => [
            PostProductionOrderAuthorise::class,
            [['productionOrderID' => $id]],
            Method::POST,
            '/ExternalApi/v2/production/order/authorise',
            [],
            ['productionOrderID' => $id],
        ],
        PostProductionOrderAuthorise::class . ' with data' => [
            PostProductionOrderAuthorise::class,
            [fn (): ProductionOrderAuthorisePostData => ProductionOrderAuthorisePostData::from(Cin7Payloads::load('production/order/authorise', 'post.request'))],
            Method::POST,
            '/ExternalApi/v2/production/order/authorise',
            [],
            [
                'productionOrderID' => 'fd5197cc-cf19-45cf-aafc-98e86647f941',
            ],
        ],
    ],
    'resources' => [
        'production order authorise' => [
            fn (Cin7Connector $cin7): mixed => $cin7->production()->order()->authorise(['productionOrderID' => $id]),
            PostProductionOrderAuthorise::class,
        ],
    ],
    'dtos' => [
        PostProductionOrderAuthorise::class => [PostProductionOrderAuthorise::class, [[]], Cin7Payloads::load('production/order/authorise', 'post.response'), ProductionOrdersData::class, ''],
    ],
    'bodies' => [
        'ProductionOrderAuthorisePostData production/order/authorise' => [ProductionOrderAuthorisePostData::class, PostProductionOrderAuthorise::class, Cin7Payloads::load('production/order/authorise', 'post.request')],
    ],
];
