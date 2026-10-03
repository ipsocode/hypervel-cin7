<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Me;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactPostData;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactPutData;
use Ipsocode\Cin7\Enums\ContactType;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Me\Contacts\DeleteMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\GetMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\PostMeContacts;
use Ipsocode\Cin7\Requests\Me\Contacts\PutMeContacts;

/**
 * `me/contacts`, the company's contacts.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ContactsResource extends BaseResource
{
    /**
     * One page of the company's contacts; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the contact with this ID
     * @param null|string $name only contacts whose name starts with this
     * @param null|ContactType $type only contacts of this type
     * @param null|bool $defaultForType only contacts that are the default for their type
     * @param null|string $phone only contacts with this phone number
     * @param null|string $fax only contacts with this fax number
     * @param null|string $email only contacts with this e-mail address
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?ContactType $type = null,
        ?bool $defaultForType = null,
        ?string $phone = null,
        ?string $fax = null,
        ?string $email = null,
    ): Response {
        return $this->connector->send(new GetMeContacts(
            $page,
            $limit,
            $id,
            $name,
            $type,
            $defaultForType,
            $phone,
            $fax,
            $email,
        ));
    }

    /**
     * Every page of the company's contacts, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the contact with this ID
     * @param null|string $name only contacts whose name starts with this
     * @param null|ContactType $type only contacts of this type
     * @param null|bool $defaultForType only contacts that are the default for their type
     * @param null|string $phone only contacts with this phone number
     * @param null|string $fax only contacts with this fax number
     * @param null|string $email only contacts with this e-mail address
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?ContactType $type = null,
        ?bool $defaultForType = null,
        ?string $phone = null,
        ?string $fax = null,
        ?string $email = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetMeContacts(
            null,
            $limit,
            $id,
            $name,
            $type,
            $defaultForType,
            $phone,
            $fax,
            $email,
        ));
    }

    /**
     * @param array<string, mixed>|MeContactPostData $body
     */
    public function post(array|MeContactPostData $body): Response
    {
        return $this->connector->send(new PostMeContacts($body));
    }

    /**
     * @param array<string, mixed>|MeContactPutData $body
     */
    public function put(array|MeContactPutData $body): Response
    {
        return $this->connector->send(new PutMeContacts($body));
    }

    /**
     * Delete one of the company's contacts.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteMeContacts($id));
    }
}
