<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Contacts;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST me/contacts`, body is a `MeContactPostData`; the response is the list envelope holding the
 * saved contact.
 *
 * @extends WriteRequest<MeContactData>
 */
final class PostMeContacts extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = MeContactData::class;

    protected ?string $dtoPath = 'MeContactsList.0';

    public function resolveEndpoint(): string
    {
        return 'me/contacts';
    }
}
