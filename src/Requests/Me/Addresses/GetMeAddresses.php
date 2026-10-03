<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Addresses;

use Ipsocode\Cin7\Data\Me\Addresses\MeAddressData;
use Ipsocode\Cin7\Enums\AddressType;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET me/addresses` — the list envelope is keyed `MeAddressesList`.
 *
 * @extends ListRequest<MeAddressData>
 */
final class GetMeAddresses extends ListRequest
{
    protected string $listKey = 'MeAddressesList';

    protected string $item = MeAddressData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?AddressType $type = null,
        protected readonly ?bool $defaultForType = null,
        protected readonly ?string $country = null,
        protected readonly ?string $stateProvince = null,
        protected readonly ?string $citySuburb = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'me/addresses';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Type' => $this->type,
            'DefaultForType' => $this->defaultForType,
            'Country' => $this->country,
            'StateProvince' => $this->stateProvince,
            'CitySuburb' => $this->citySuburb,
        ];
    }
}
