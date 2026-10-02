<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Requests\Me\GetMe;
use Ipsocode\Cin7\Resources\Me\AddressesResource;
use Ipsocode\Cin7\Resources\Me\ContactsResource;

/**
 * `me`, the company the API application belongs to; `addresses()` and `contacts()` are the
 * `me/…` sub-resources.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class MeResource extends BaseResource
{
    /**
     * The company's name, base currency and time zone, and the settings its documents follow.
     */
    public function get(): Response
    {
        return $this->connector->send(new GetMe);
    }

    /**
     * The `me/addresses` resource, the company's addresses.
     */
    public function addresses(): AddressesResource
    {
        return new AddressesResource($this->connector);
    }

    /**
     * The `me/contacts` resource, the company's contacts.
     */
    public function contacts(): ContactsResource
    {
        return new ContactsResource($this->connector);
    }
}
