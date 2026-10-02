<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierData;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierPostData;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierPutData;
use Ipsocode\Cin7\Requests\Ref\Carrier\DeleteCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\GetCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\PostCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\PutCarrier;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/carrier`; tests/Catalogue.php merges every file's rows by kind.

$id = '8a5697fa-4c0b-46a5-a5bf-1864cb76623f';
$uri = '/ExternalApi/v2/ref/carrier';

return [
    'requests' => [
        GetCarrier::class => [GetCarrier::class, ['carrierId' => $id, 'description' => 'NEW'], Method::GET, $uri, ['CarrierID' => $id, 'Description' => 'NEW', 'page' => 1, 'limit' => 100], null],
        PostCarrier::class => [PostCarrier::class, [['Description' => 'NEW Carrier']], Method::POST, $uri, [], ['Description' => 'NEW Carrier']],
        PutCarrier::class => [PutCarrier::class, [['CarrierID' => $id, 'Description' => 'UPDATED']], Method::PUT, $uri, [], ['CarrierID' => $id, 'Description' => 'UPDATED']],
        DeleteCarrier::class => [DeleteCarrier::class, [$id], Method::DELETE, $uri, ['ID' => $id], null],
        PostCarrier::class . ' with data' => [PostCarrier::class, [fn (): CarrierPostData => CarrierPostData::from(['Description' => 'NEW Carrier'])], Method::POST, $uri, [], ['Description' => 'NEW Carrier']],
        PutCarrier::class . ' with data' => [PutCarrier::class, [fn (): CarrierPutData => CarrierPutData::from(['Description' => 'UPDATED', 'CarrierID' => $id])], Method::PUT, $uri, [], ['CarrierID' => $id, 'Description' => 'UPDATED']],
    ],
    'resources' => [
        'ref carrier get' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->get(), GetCarrier::class, Method::GET, $uri, ['page' => 1, 'limit' => 100], null],
        'ref carrier paginate' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->paginate(description: 'A')->current(), GetCarrier::class, Method::GET, $uri, ['Description' => 'A', 'page' => 1, 'limit' => 100], null],
        'ref carrier post' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->post(['Description' => 'NEW Carrier']), PostCarrier::class, Method::POST, $uri, [], ['Description' => 'NEW Carrier']],
        'ref carrier post with data' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->post(CarrierPostData::from(['Description' => 'NEW Carrier'])), PostCarrier::class, Method::POST, $uri, [], ['Description' => 'NEW Carrier']],
        'ref carrier put' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->put(['CarrierID' => $id, 'Description' => 'UPDATED']), PutCarrier::class, Method::PUT, $uri, [], ['CarrierID' => $id, 'Description' => 'UPDATED']],
        'ref carrier put with data' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->put(CarrierPutData::from(['Description' => 'UPDATED', 'CarrierID' => $id])), PutCarrier::class, Method::PUT, $uri, [], ['CarrierID' => $id, 'Description' => 'UPDATED']],
        'ref carrier delete' => [fn (Cin7Connector $cin7): mixed => $cin7->ref()->carrier()->delete($id), DeleteCarrier::class, Method::DELETE, $uri, ['ID' => $id], null],
    ],
    'dtos' => [
        GetCarrier::class => [GetCarrier::class, [], Cin7Payloads::load('ref/carrier', 'get.response'), CarrierData::class, 'CarrierList'],
        PostCarrier::class => [PostCarrier::class, [[]], Cin7Payloads::load('ref/carrier', 'post.response'), CarrierData::class, 'CarrierList'],
        PutCarrier::class => [PutCarrier::class, [[]], Cin7Payloads::load('ref/carrier', 'put.response'), CarrierData::class, 'CarrierList'],
    ],
    'bodies' => [
        CarrierPostData::class => [CarrierPostData::class, Cin7Payloads::load('ref/carrier', 'post.request')],
        CarrierPutData::class => [CarrierPutData::class, Cin7Payloads::load('ref/carrier', 'put.request')],
    ],
    'missing' => [
        'carrier POST without Description' => [CarrierPostData::class, []],
        'carrier PUT without CarrierID' => [CarrierPutData::class, Arr::except(Cin7Payloads::load('ref/carrier', 'put.request'), 'CarrierID')],
        'carrier without Description' => [CarrierData::class, ['CarrierID' => $id]],
    ],
    'required' => [
        CarrierData::class => ['Description'],
        CarrierPostData::class => ['Description'],
        CarrierPutData::class => ['Description', 'CarrierID'],
    ],
];
