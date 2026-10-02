<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetData;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetLineData;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetPostData;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetPutData;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\DeleteAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\GetAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\PostAttributeSet;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\PutAttributeSet;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/attributeset`; tests/Catalogue.php merges every file's rows by kind.

// The fields every attribute set requires on a write: its name and its first attribute.
$first = ['Attribute1Name' => 'Colour', 'Attribute1Type' => 'List', 'Attribute1Values' => 'Red, Blue'];
$id = '0ba27d98-42ca-4bf6-9b9e-9f71538bd53d';

return [
    'requests' => [
        GetAttributeSet::class => [
            GetAttributeSet::class,
            ['id' => $id, 'name' => 'Test'],
            Method::GET,
            '/ExternalApi/v2/ref/attributeset',
            ['ID' => $id, 'Name' => 'Test', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostAttributeSet::class => [
            PostAttributeSet::class,
            [['Name' => 'Test set']],
            Method::POST,
            '/ExternalApi/v2/ref/attributeset',
            [],
            ['Name' => 'Test set'],
        ],
        PutAttributeSet::class => [
            PutAttributeSet::class,
            [['ID' => $id, 'Name' => 'Test set']],
            Method::PUT,
            '/ExternalApi/v2/ref/attributeset',
            [],
            ['ID' => $id, 'Name' => 'Test set'],
        ],
        DeleteAttributeSet::class => [
            DeleteAttributeSet::class,
            [$id],
            Method::DELETE,
            '/ExternalApi/v2/ref/attributeset',
            ['ID' => $id],
            null,
        ],
        PostAttributeSet::class . ' with data' => [
            PostAttributeSet::class,
            [fn (): AttributeSetPostData => AttributeSetPostData::from(['Name' => 'Test set', ...$first, 'Attribute2Name' => 'Size', 'Attribute2Type' => 'Text'])],
            Method::POST,
            '/ExternalApi/v2/ref/attributeset',
            [],
            [...$first, 'Name' => 'Test set', 'Attribute2Name' => 'Size', 'Attribute2Type' => 'Text'],
        ],
        PutAttributeSet::class . ' with data' => [
            PutAttributeSet::class,
            [fn (): AttributeSetPutData => AttributeSetPutData::from(['Name' => 'Test set', ...$first, 'ID' => $id])],
            Method::PUT,
            '/ExternalApi/v2/ref/attributeset',
            [],
            [...$first, 'ID' => $id, 'Name' => 'Test set'],
        ],
    ],
    'resources' => [
        'ref attributeSet get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->get(),
            GetAttributeSet::class,
            Method::GET,
            '/ExternalApi/v2/ref/attributeset',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref attributeSet paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->paginate(name: 'A')->current(),
            GetAttributeSet::class,
            Method::GET,
            '/ExternalApi/v2/ref/attributeset',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref attributeSet post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->post(['Name' => 'Test set']),
            PostAttributeSet::class,
            Method::POST,
            '/ExternalApi/v2/ref/attributeset',
            [],
            ['Name' => 'Test set'],
        ],
        'ref attributeSet post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->post(AttributeSetPostData::from(['Name' => 'Test set', ...$first])),
            PostAttributeSet::class,
            Method::POST,
            '/ExternalApi/v2/ref/attributeset',
            [],
            [...$first, 'Name' => 'Test set'],
        ],
        'ref attributeSet put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->put(['ID' => $id, 'Name' => 'Test set']),
            PutAttributeSet::class,
            Method::PUT,
            '/ExternalApi/v2/ref/attributeset',
            [],
            ['ID' => $id, 'Name' => 'Test set'],
        ],
        'ref attributeSet put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->put(AttributeSetPutData::from(['Name' => 'Test set', ...$first, 'ID' => $id])),
            PutAttributeSet::class,
            Method::PUT,
            '/ExternalApi/v2/ref/attributeset',
            [],
            [...$first, 'ID' => $id, 'Name' => 'Test set'],
        ],
        'ref attributeSet delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->attributeSet()->delete($id),
            DeleteAttributeSet::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/attributeset',
            ['ID' => $id],
            null,
        ],
    ],
    'dtos' => [
        GetAttributeSet::class => [GetAttributeSet::class, [], Cin7Payloads::load('ref/attributeset', 'get.response'), AttributeSetData::class, 'AttributeSetList'],
        PostAttributeSet::class => [PostAttributeSet::class, [[]], Cin7Payloads::load('ref/attributeset', 'post.response'), AttributeSetData::class, ''],
        PutAttributeSet::class => [PutAttributeSet::class, [[]], Cin7Payloads::load('ref/attributeset', 'put.response'), AttributeSetData::class, ''],
    ],
    'bodies' => [
        AttributeSetPostData::class => [AttributeSetPostData::class, Cin7Payloads::load('ref/attributeset', 'post.request')],
        AttributeSetPutData::class => [AttributeSetPutData::class, Cin7Payloads::load('ref/attributeset', 'put.request')],
    ],
    'missing' => [
        'attribute set POST without Attribute1Type' => [AttributeSetPostData::class, Arr::except(Cin7Payloads::load('ref/attributeset', 'post.request'), 'Attribute1Type')],
        'attribute set PUT without ID' => [AttributeSetPutData::class, Arr::except(Cin7Payloads::load('ref/attributeset', 'put.request'), 'ID')],
        'attribute set without Name' => [AttributeSetData::class, Arr::except(Cin7Payloads::load('ref/attributeset', 'post.response'), 'Name')],
    ],
    'required' => [
        AttributeSetData::class => ['Name'],
        AttributeSetPostData::class => ['Name', 'Attribute1Name', 'Attribute1Type', 'Attribute1Values'],
        AttributeSetPutData::class => ['Name', 'Attribute1Name', 'Attribute1Type', 'Attribute1Values', 'ID'],
        AttributeSetLineData::class => [],
    ],
];
