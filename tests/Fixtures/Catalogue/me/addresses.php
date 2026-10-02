<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressData;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressPostData;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressPutData;
use Ipsocode\Cin7\Enums\AddressType;
use Ipsocode\Cin7\Requests\Me\Addresses\DeleteMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\GetMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\PostMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\PutMeAddresses;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `me/addresses`; tests/Catalogue.php merges every file's rows by kind.

// The fields every address requires.
$fields = ['Line1' => 'DEFAULT business address', 'CitySuburb' => 'DEFAULT City', 'StateProvince' => 'DEFAULT State', 'ZipPostCode' => 'DEFAULT Postcode', 'Country' => 'Russia', 'Type' => 'Business'];

return [
    'requests' => [
        GetMeAddresses::class => [
            GetMeAddresses::class,
            ['id' => 'd8309201-3498-47a2-99cd-b14786e0753b', 'type' => AddressType::Billing, 'defaultForType' => true, 'country' => 'Australia', 'stateProvince' => 'DEFAULT State', 'citySuburb' => 'DEFAULT City'],
            Method::GET,
            '/ExternalApi/v2/me/addresses',
            ['ID' => 'd8309201-3498-47a2-99cd-b14786e0753b', 'Type' => 'Billing', 'DefaultForType' => 'true', 'Country' => 'Australia', 'StateProvince' => 'DEFAULT State', 'CitySuburb' => 'DEFAULT City', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostMeAddresses::class => [
            PostMeAddresses::class,
            [['Line1' => 'DEFAULT business address']],
            Method::POST,
            '/ExternalApi/v2/me/addresses',
            [],
            ['Line1' => 'DEFAULT business address'],
        ],
        PutMeAddresses::class => [
            PutMeAddresses::class,
            [['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'Line1' => 'EDITED business address']],
            Method::PUT,
            '/ExternalApi/v2/me/addresses',
            [],
            ['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'Line1' => 'EDITED business address'],
        ],
        DeleteMeAddresses::class => [
            DeleteMeAddresses::class,
            ['ded03f44-00fc-4cbd-8b4d-c803c6252788'],
            Method::DELETE,
            '/ExternalApi/v2/me/addresses',
            ['ID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788'],
            null,
        ],
        PostMeAddresses::class . ' with data' => [
            PostMeAddresses::class,
            [fn (): MeAddressPostData => MeAddressPostData::from([...$fields, 'Line2' => null, 'DefaultForType' => true])],
            Method::POST,
            '/ExternalApi/v2/me/addresses',
            [],
            ['DefaultForType' => true, ...$fields],
        ],
        PutMeAddresses::class . ' with data' => [
            PutMeAddresses::class,
            [fn (): MeAddressPutData => MeAddressPutData::from([...$fields, 'AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788'])],
            Method::PUT,
            '/ExternalApi/v2/me/addresses',
            [],
            ['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', ...$fields],
        ],
    ],
    'resources' => [
        'me addresses get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->get(),
            GetMeAddresses::class,
            Method::GET,
            '/ExternalApi/v2/me/addresses',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'me addresses paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->paginate(type: AddressType::Shipping)->current(),
            GetMeAddresses::class,
            Method::GET,
            '/ExternalApi/v2/me/addresses',
            ['Type' => 'Shipping', 'page' => 1, 'limit' => 100],
            null,
        ],
        'me addresses post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->post(['Line1' => 'DEFAULT business address']),
            PostMeAddresses::class,
            Method::POST,
            '/ExternalApi/v2/me/addresses',
            [],
            ['Line1' => 'DEFAULT business address'],
        ],
        'me addresses post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->post(MeAddressPostData::from($fields)),
            PostMeAddresses::class,
            Method::POST,
            '/ExternalApi/v2/me/addresses',
            [],
            $fields,
        ],
        'me addresses put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->put(['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'Line1' => 'EDITED business address']),
            PutMeAddresses::class,
            Method::PUT,
            '/ExternalApi/v2/me/addresses',
            [],
            ['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'Line1' => 'EDITED business address'],
        ],
        'me addresses put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->put(MeAddressPutData::from([...$fields, 'AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'DefaultForType' => false])),
            PutMeAddresses::class,
            Method::PUT,
            '/ExternalApi/v2/me/addresses',
            [],
            ['AddressID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788', 'DefaultForType' => false, ...$fields],
        ],
        'me addresses delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->addresses()->delete('ded03f44-00fc-4cbd-8b4d-c803c6252788'),
            DeleteMeAddresses::class,
            Method::DELETE,
            '/ExternalApi/v2/me/addresses',
            ['ID' => 'ded03f44-00fc-4cbd-8b4d-c803c6252788'],
            null,
        ],
    ],
    'dtos' => [
        GetMeAddresses::class => [GetMeAddresses::class, [], Cin7Payloads::load('me/addresses', 'get.response'), MeAddressData::class, 'MeAddressesList'],
        PostMeAddresses::class => [PostMeAddresses::class, [[]], Cin7Payloads::load('me/addresses', 'post.response'), MeAddressData::class, 'MeAddressesList.0'],
        PutMeAddresses::class => [PutMeAddresses::class, [[]], Cin7Payloads::load('me/addresses', 'put.response'), MeAddressData::class, 'MeAddressesList.0'],
    ],
    'bodies' => [
        MeAddressPostData::class => [MeAddressPostData::class, Cin7Payloads::load('me/addresses', 'post.request')],
        MeAddressPutData::class => [MeAddressPutData::class, Cin7Payloads::load('me/addresses', 'put.request')],
    ],
    'missing' => [
        'me address POST without Type' => [MeAddressPostData::class, Arr::except(Cin7Payloads::load('me/addresses', 'post.request'), 'Type')],
        'me address PUT without AddressID' => [MeAddressPutData::class, Arr::except(Cin7Payloads::load('me/addresses', 'put.request'), 'AddressID')],
        'me address without ZipPostCode' => [MeAddressData::class, Arr::except(Cin7Payloads::load('me/addresses', 'get.response')['MeAddressesList'][0], 'ZipPostCode')],
    ],
    'required' => [
        MeAddressData::class => ['Line1', 'CitySuburb', 'StateProvince', 'ZipPostCode', 'Country', 'Type'],
        MeAddressPostData::class => ['Line1', 'CitySuburb', 'StateProvince', 'ZipPostCode', 'Country', 'Type'],
        MeAddressPutData::class => ['Line1', 'CitySuburb', 'StateProvince', 'ZipPostCode', 'Country', 'Type', 'AddressID'],
    ],
];
