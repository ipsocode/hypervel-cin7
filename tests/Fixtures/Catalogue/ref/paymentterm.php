<?php

declare(strict_types=1);

use Hypervel\Saloon\Enums\Method;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermData;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermPostData;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermPutData;
use Ipsocode\Cin7\Enums\PaymentTermMethod;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\DeletePaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\GetPaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\PostPaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\PutPaymentTerm;
use Workbench\App\Support\Cin7Payloads;

// The catalogue rows for `ref/paymentterm`; tests/Catalogue.php merges every file's rows by kind.

// The fields every ref paymentterm requires.
$fields = ['Name' => '5 days since end of month'];

return [
    'requests' => [
        GetPaymentTerm::class => [
            GetPaymentTerm::class,
            ['id' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'name' => '30', 'termMethod' => PaymentTermMethod::NumberOfDays, 'isActive' => true, 'isDefault' => false],
            Method::GET,
            '/ExternalApi/v2/ref/paymentterm',
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'Name' => '30', 'Method' => 'number of days', 'IsActive' => 'true', 'IsDefault' => 'false', 'page' => 1, 'limit' => 100],
            null,
        ],
        PostPaymentTerm::class => [
            PostPaymentTerm::class,
            [['Name' => '5 days since end of month', 'Duration' => 5]],
            Method::POST,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['Name' => '5 days since end of month', 'Duration' => 5],
        ],
        PutPaymentTerm::class => [
            PutPaymentTerm::class,
            [['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'Name' => '30 days']],
            Method::PUT,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'Name' => '30 days'],
        ],
        DeletePaymentTerm::class => [
            DeletePaymentTerm::class,
            ['927a7013-e1d9-4194-a547-28b1b4c6b413'],
            Method::DELETE,
            '/ExternalApi/v2/ref/paymentterm',
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413'],
            null,
        ],
        PostPaymentTerm::class . ' with data' => [
            PostPaymentTerm::class,
            [fn (): PaymentTermPostData => PaymentTermPostData::from([...$fields, 'Method' => 'number of days', 'IsActive' => true])],
            Method::POST,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['Method' => 'number of days', 'IsActive' => true, ...$fields],
        ],
        PutPaymentTerm::class . ' with data' => [
            PutPaymentTerm::class,
            [fn (): PaymentTermPutData => PaymentTermPutData::from([...$fields, 'ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'IsDefault' => true])],
            Method::PUT,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'IsDefault' => true, ...$fields],
        ],
    ],
    'resources' => [
        'ref paymentTerm get' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->get(),
            GetPaymentTerm::class,
            Method::GET,
            '/ExternalApi/v2/ref/paymentterm',
            ['page' => 1, 'limit' => 100],
            null,
        ],
        'ref paymentTerm paginate' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->paginate(name: 'A')->current(),
            GetPaymentTerm::class,
            Method::GET,
            '/ExternalApi/v2/ref/paymentterm',
            ['Name' => 'A', 'page' => 1, 'limit' => 100],
            null,
        ],
        'ref paymentTerm post' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->post(['Name' => '5 days since end of month', 'Duration' => 5]),
            PostPaymentTerm::class,
            Method::POST,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['Name' => '5 days since end of month', 'Duration' => 5],
        ],
        'ref paymentTerm post with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->post(PaymentTermPostData::from($fields)),
            PostPaymentTerm::class,
            Method::POST,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            $fields,
        ],
        'ref paymentTerm put' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->put(['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'Name' => '30 days']),
            PutPaymentTerm::class,
            Method::PUT,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'Name' => '30 days'],
        ],
        'ref paymentTerm put with data' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->put(PaymentTermPutData::from([...$fields, 'ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'IsDefault' => true])),
            PutPaymentTerm::class,
            Method::PUT,
            '/ExternalApi/v2/ref/paymentterm',
            [],
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413', 'IsDefault' => true, ...$fields],
        ],
        'ref paymentTerm delete' => [
            fn (Cin7Connector $cin7): mixed => $cin7->ref()->paymentTerm()->delete('927a7013-e1d9-4194-a547-28b1b4c6b413'),
            DeletePaymentTerm::class,
            Method::DELETE,
            '/ExternalApi/v2/ref/paymentterm',
            ['ID' => '927a7013-e1d9-4194-a547-28b1b4c6b413'],
            null,
        ],
    ],
    'dtos' => [
        GetPaymentTerm::class => [GetPaymentTerm::class, [], Cin7Payloads::load('ref/paymentterm', 'get.response'), PaymentTermData::class, 'PaymentTermList'],
        PostPaymentTerm::class => [PostPaymentTerm::class, [[]], Cin7Payloads::load('ref/paymentterm', 'post.response'), PaymentTermData::class, 'PaymentTermList.0'],
        PutPaymentTerm::class => [PutPaymentTerm::class, [[]], Cin7Payloads::load('ref/paymentterm', 'put.response'), PaymentTermData::class, 'PaymentTermList.0'],
    ],
    'bodies' => [
        PaymentTermPostData::class => [PaymentTermPostData::class, Cin7Payloads::load('ref/paymentterm', 'post.request')],
        PaymentTermPutData::class => [PaymentTermPutData::class, Cin7Payloads::load('ref/paymentterm', 'put.request')],
    ],
    'missing' => [
        'ref paymentterm POST without Name' => [PaymentTermPostData::class, Arr::except(Cin7Payloads::load('ref/paymentterm', 'post.request'), 'Name')],
        'ref paymentterm PUT without ID' => [PaymentTermPutData::class, Arr::except(Cin7Payloads::load('ref/paymentterm', 'put.request'), 'ID')],
        'ref paymentterm without Name' => [PaymentTermData::class, Arr::except(Cin7Payloads::load('ref/paymentterm', 'get.response')['PaymentTermList'][0], 'Name')],
    ],
    'required' => [
        PaymentTermData::class => ['Name'],
        PaymentTermPostData::class => ['Name'],
        PaymentTermPutData::class => ['Name', 'ID'],
    ],
];
