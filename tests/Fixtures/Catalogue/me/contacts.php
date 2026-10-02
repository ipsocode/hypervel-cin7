<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactData;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactPostData;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactPutData;
use Ipsocode\Cin7\Enums\ContactType;
use Ipsocode\Cin7\Requests\Me\Contacts\DeleteMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\GetMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\PostMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\PutMeContacts;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `me/contacts`; tests/Catalogue.php merges every file's rows by kind.

return [
    'requests' => [
        GetMeContacts::class => [
            GetMeContacts::class,
            ['id' => '1a917f5a-4d68-41a0-8b07-7b26841ffb9d', 'name' => 'DEFAULT', 'type' => ContactType::Employee, 'defaultForType' => false, 'phone' => '12345678', 'fax' => '87654321', 'email' => 'contact@example.com'],
            Method::GET,
            '/ExternalApi/v2/me/contacts',
            ['ID' => '1a917f5a-4d68-41a0-8b07-7b26841ffb9d', 'Name' => 'DEFAULT', 'Type' => 'Employee', 'DefaultForType' => 'false', 'Phone' => '12345678', 'Fax' => '87654321', 'Email' => 'contact@example.com', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostMeContacts::class => [
            PostMeContacts::class,
            [['Name' => 'DEFAULT Business contact']],
            Method::POST,
            '/ExternalApi/v2/me/contacts',
            [],
            ['Name' => 'DEFAULT Business contact'],
        ],
        PutMeContacts::class => [
            PutMeContacts::class,
            [['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact']],
            Method::PUT,
            '/ExternalApi/v2/me/contacts',
            [],
            ['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact'],
        ],
        DeleteMeContacts::class => [
            DeleteMeContacts::class,
            ['62ba5a66-9c7e-44d1-b2b7-2eb72d946e18'],
            Method::DELETE,
            '/ExternalApi/v2/me/contacts',
            ['ID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18'],
            null,
        ],
        PostMeContacts::class . ' with data' => [
            PostMeContacts::class,
            [fn (): MeContactPostData => MeContactPostData::from(['Name' => 'DEFAULT Business contact', 'Phone' => '12345678', 'Fax' => null, 'Type' => 'Business', 'DefaultForType' => true])],
            Method::POST,
            '/ExternalApi/v2/me/contacts',
            [],
            ['Phone' => '12345678', 'Type' => 'Business', 'DefaultForType' => true, 'Name' => 'DEFAULT Business contact'],
        ],
        PutMeContacts::class . ' with data' => [
            PutMeContacts::class,
            [fn (): MeContactPutData => MeContactPutData::from(['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact', 'Email' => 'test_put@example.com'])],
            Method::PUT,
            '/ExternalApi/v2/me/contacts',
            [],
            ['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Email' => 'test_put@example.com', 'Name' => 'DEFAULT PUT TEST Business contact'],
        ],
    ],
    'resources' => [
        'me contacts get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->get(),
            GetMeContacts::class,
            Method::GET,
            '/ExternalApi/v2/me/contacts',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'me contacts paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->paginate(name: 'DEFAULT')->current(),
            GetMeContacts::class,
            Method::GET,
            '/ExternalApi/v2/me/contacts',
            ['Name' => 'DEFAULT', 'page' => 1, 'limit' => 100],
            null,
        ],
        'me contacts post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->post(['Name' => 'DEFAULT Business contact']),
            PostMeContacts::class,
            Method::POST,
            '/ExternalApi/v2/me/contacts',
            [],
            ['Name' => 'DEFAULT Business contact'],
        ],
        'me contacts post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->post(MeContactPostData::from(['Name' => 'DEFAULT Business contact'])),
            PostMeContacts::class,
            Method::POST,
            '/ExternalApi/v2/me/contacts',
            [],
            ['Name' => 'DEFAULT Business contact'],
        ],
        'me contacts put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->put(['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact']),
            PutMeContacts::class,
            Method::PUT,
            '/ExternalApi/v2/me/contacts',
            [],
            ['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact'],
        ],
        'me contacts put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->put(MeContactPutData::from(['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Name' => 'DEFAULT PUT TEST Business contact', 'Type' => 'Employee'])),
            PutMeContacts::class,
            Method::PUT,
            '/ExternalApi/v2/me/contacts',
            [],
            ['ContactID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18', 'Type' => 'Employee', 'Name' => 'DEFAULT PUT TEST Business contact'],
        ],
        'me contacts delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->me()->contacts()->delete('62ba5a66-9c7e-44d1-b2b7-2eb72d946e18'),
            DeleteMeContacts::class,
            Method::DELETE,
            '/ExternalApi/v2/me/contacts',
            ['ID' => '62ba5a66-9c7e-44d1-b2b7-2eb72d946e18'],
            null,
        ],
    ],
    'dtos' => [
        GetMeContacts::class => [GetMeContacts::class, [], Cin7Payloads::load('me/contacts', 'get.response'), MeContactData::class, 'MeContactsList'],
        PostMeContacts::class => [PostMeContacts::class, [[]], Cin7Payloads::load('me/contacts', 'post.response'), MeContactData::class, 'MeContactsList.0'],
        PutMeContacts::class => [PutMeContacts::class, [[]], Cin7Payloads::load('me/contacts', 'put.response'), MeContactData::class, 'MeContactsList.0'],
    ],
    'bodies' => [
        MeContactPostData::class => [MeContactPostData::class, Cin7Payloads::load('me/contacts', 'post.request')],
        MeContactPutData::class => [MeContactPutData::class, Cin7Payloads::load('me/contacts', 'put.request')],
    ],
    'missing' => [
        'me contact POST without Name' => [MeContactPostData::class, Arr::except(Cin7Payloads::load('me/contacts', 'post.request'), 'Name')],
        'me contact PUT without ContactID' => [MeContactPutData::class, Arr::except(Cin7Payloads::load('me/contacts', 'put.request'), 'ContactID')],
        'me contact without Name' => [MeContactData::class, Arr::except(Cin7Payloads::load('me/contacts', 'get.response')['MeContactsList'][0], 'Name')],
    ],
    'required' => [
        MeContactData::class => ['Name'],
        MeContactPostData::class => ['Name'],
        MeContactPutData::class => ['Name', 'ContactID'],
    ],
];
