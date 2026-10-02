<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Location\LocationData;
use Ipsocode\Cin7\Data\Ref\Location\LocationPostData;
use Ipsocode\Cin7\Data\Ref\Location\LocationPutData;
use Ipsocode\Cin7\Requests\Ref\Location\DeleteLocation;
use Ipsocode\Cin7\Requests\Ref\Location\GetLocation;
use Ipsocode\Cin7\Requests\Ref\Location\PostLocation;
use Ipsocode\Cin7\Requests\Ref\Location\PutLocation;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/location`; tests/Catalogue.php merges every file's rows by kind.

$id = '7e1e7cdc-eb4a-45d4-ae7f-e8999bb9dd8a';
$uri = '/ExternalApi/v2/ref/location';

return [
    'requests' => [
        GetLocation::class => [GetLocation::class, ['id' => $id, 'deprecated' => true, 'name' => 'Test'], Method::GET, $uri, ['ID' => $id, 'Deprecated' => 'true', 'Name' => 'Test', 'page' => 1, 'limit' => 100], null],
        PostLocation::class => [PostLocation::class, [['Name' => 'Test']], Method::POST, $uri, [], ['Name' => 'Test']],
        PutLocation::class => [PutLocation::class, [['ID' => $id, 'Name' => 'Test1']], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'Test1']],
        DeleteLocation::class => [DeleteLocation::class, [$id], Method::DELETE, $uri, ['ID' => $id], null],
        PostLocation::class . ' with data' => [PostLocation::class, [fn (): LocationPostData => LocationPostData::from(['Name' => 'Test', 'IsShopfloor' => true])], Method::POST, $uri, [], ['IsShopfloor' => true, 'Name' => 'Test']],
        PutLocation::class . ' with data' => [PutLocation::class, [fn (): LocationPutData => LocationPutData::from(['Name' => 'Test1', 'ID' => $id])], Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'Test1']],
    ],
    'resources' => [
        'ref location get' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->get(), GetLocation::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'ref location paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->paginate(name: 'A', deprecated: false)->current(), GetLocation::class, Method::GET, $uri, ['Deprecated' => 'false', 'Name' => 'A', 'page' => 1, 'limit' => 100], null],
        'ref location post' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->post(['Name' => 'Test']), PostLocation::class, Method::POST, $uri, [], ['Name' => 'Test']],
        'ref location post with data' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->post(LocationPostData::from(['Name' => 'Test'])), PostLocation::class, Method::POST, $uri, [], ['Name' => 'Test']],
        'ref location put' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->put(['ID' => $id, 'Name' => 'Test1']), PutLocation::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'Test1']],
        'ref location put with data' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->put(LocationPutData::from(['Name' => 'Test1', 'ID' => $id])), PutLocation::class, Method::PUT, $uri, [], ['ID' => $id, 'Name' => 'Test1']],
        'ref location delete' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->location()->delete($id), DeleteLocation::class, Method::DELETE, $uri, ['ID' => $id], null],
    ],
    'dtos' => [
        GetLocation::class => [GetLocation::class, [], Cin7Payloads::load('ref/location', 'get.response'), LocationData::class, 'LocationList'],
        PostLocation::class => [PostLocation::class, [[]], Cin7Payloads::load('ref/location', 'post.response'), LocationData::class, ''],
        PutLocation::class => [PutLocation::class, [[]], Cin7Payloads::load('ref/location', 'put.response'), LocationData::class, ''],
    ],
    'bodies' => [
        LocationPostData::class => [LocationPostData::class, Cin7Payloads::load('ref/location', 'post.request')],
        LocationPutData::class => [LocationPutData::class, Cin7Payloads::load('ref/location', 'put.request')],
    ],
    'missing' => [
        'location POST without Name' => [LocationPostData::class, Arr::except(Cin7Payloads::load('ref/location', 'post.request'), 'Name')],
        'location PUT without ID' => [LocationPutData::class, Arr::except(Cin7Payloads::load('ref/location', 'put.request'), 'ID')],
        'location without Name' => [LocationData::class, Arr::except(Cin7Payloads::load('ref/location', 'post.response'), 'Name')],
    ],
    'required' => [
        LocationData::class => ['Name'],
        LocationPostData::class => ['Name'],
        LocationPutData::class => ['Name', 'ID'],
    ],
];
