<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Account\AccountData;
use Ipsocode\Cin7\Data\Ref\Account\AccountPostData;
use Ipsocode\Cin7\Data\Ref\Account\AccountPutData;
use Ipsocode\Cin7\Requests\Ref\Account\DeleteAccount;
use Ipsocode\Cin7\Requests\Ref\Account\GetAccount;
use Ipsocode\Cin7\Requests\Ref\Account\PostAccount;
use Ipsocode\Cin7\Requests\Ref\Account\PutAccount;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/account`; tests/Catalogue.php merges every file's rows by kind.

// The fields every account requires.
$fields = ['Code' => '001', 'Name' => 'Accounts Payable test', 'Type' => 'CURRLIAB', 'Status' => 'ACTIVE'];

return [
    'requests' => [
        GetAccount::class => [
            GetAccount::class,
            ['code' => '800', 'name' => 'Accounts', 'type' => 'CURRLIAB', 'status' => 'ACTIVE'],
            Method::GET,
            '/ExternalApi/v2/ref/account',
            ['Code' => '800', 'Name' => 'Accounts', 'Type' => 'CURRLIAB', 'Status' => 'ACTIVE', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostAccount::class => [
            PostAccount::class,
            [['Code' => '001', 'Name' => 'Accounts Payable test']],
            Method::POST,
            '/ExternalApi/v2/ref/account',
            [],
            ['Code' => '001', 'Name' => 'Accounts Payable test'],
        ],
        PutAccount::class => [
            PutAccount::class,
            [['Code' => '001', 'Name' => 'Accounts Payable put test']],
            Method::PUT,
            '/ExternalApi/v2/ref/account',
            [],
            ['Code' => '001', 'Name' => 'Accounts Payable put test'],
        ],
        DeleteAccount::class => [
            DeleteAccount::class,
            ['001'],
            Method::DELETE,
            '/ExternalApi/v2/ref/account',
            ['Code' => '001'],
            null,
        ],
        PostAccount::class . ' with data' => [
            PostAccount::class,
            [fn (): AccountPostData => AccountPostData::from([...$fields, 'SystemAccount' => 'Accounts payable', 'Class' => 'LIABILITY'])],
            Method::POST,
            '/ExternalApi/v2/ref/account',
            [],
            ['SystemAccount' => 'Accounts payable', 'Class' => 'LIABILITY', ...$fields],
        ],
        PutAccount::class . ' with data' => [
            PutAccount::class,
            [fn (): AccountPutData => AccountPutData::from([...$fields, 'Description' => 'Put test description'])],
            Method::PUT,
            '/ExternalApi/v2/ref/account',
            [],
            ['Description' => 'Put test description', ...$fields],
        ],
    ],
    'resources' => [
        'ref account get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->get(),
            GetAccount::class,
            Method::GET,
            '/ExternalApi/v2/ref/account',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref account paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->paginate(type: 'BANK')->current(),
            GetAccount::class,
            Method::GET,
            '/ExternalApi/v2/ref/account',
            ['Type' => 'BANK', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref account post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->post(['Code' => '001', 'Name' => 'Accounts Payable test']),
            PostAccount::class,
            Method::POST,
            '/ExternalApi/v2/ref/account',
            [],
            ['Code' => '001', 'Name' => 'Accounts Payable test'],
        ],
        'ref account post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->post(AccountPostData::from($fields)),
            PostAccount::class,
            Method::POST,
            '/ExternalApi/v2/ref/account',
            [],
            $fields,
        ],
        'ref account put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->put(['Code' => '001', 'Name' => 'Accounts Payable put test']),
            PutAccount::class,
            Method::PUT,
            '/ExternalApi/v2/ref/account',
            [],
            ['Code' => '001', 'Name' => 'Accounts Payable put test'],
        ],
        'ref account put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->put(AccountPutData::from([...$fields, 'Type' => 'BANK', 'Bank' => 'Bank of Example', 'BankAccountNumber' => '12345678'])),
            PutAccount::class,
            Method::PUT,
            '/ExternalApi/v2/ref/account',
            [],
            ['Bank' => 'Bank of Example', 'BankAccountNumber' => '12345678', ...$fields, 'Type' => 'BANK'],
        ],
        'ref account delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->account()->delete('001'),
            DeleteAccount::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/account',
            ['Code' => '001'],
            null,
        ],
    ],
    'dtos' => [
        GetAccount::class => [GetAccount::class, [], Cin7Payloads::load('ref/account', 'get.response'), AccountData::class, 'AccountsList'],
        PostAccount::class => [PostAccount::class, [[]], Cin7Payloads::load('ref/account', 'post.response'), AccountData::class, 'AccountsList.0'],
        PutAccount::class => [PutAccount::class, [[]], Cin7Payloads::load('ref/account', 'put.response'), AccountData::class, 'AccountsList.0'],
    ],
    'bodies' => [
        AccountPostData::class => [AccountPostData::class, Cin7Payloads::load('ref/account', 'post.request')],
        AccountPutData::class => [AccountPutData::class, Cin7Payloads::load('ref/account', 'put.request')],
    ],
    'missing' => [
        'account POST without Code' => [AccountPostData::class, Arr::except(Cin7Payloads::load('ref/account', 'post.request'), 'Code')],
        'account PUT without Type' => [AccountPutData::class, Arr::except(Cin7Payloads::load('ref/account', 'put.request'), 'Type')],
        'account without Status' => [AccountData::class, Arr::except(Cin7Payloads::load('ref/account', 'get.response')['AccountsList'][0], 'Status')],
    ],
    'required' => [
        AccountData::class => ['Code', 'Name', 'Type', 'Status'],
        AccountPostData::class => ['Code', 'Name', 'Type', 'Status'],
        AccountPutData::class => ['Code', 'Name', 'Type', 'Status'],
    ],
];
