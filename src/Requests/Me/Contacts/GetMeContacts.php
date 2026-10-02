<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Me\Contacts;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Me\Contacts\MeContactData;
use Ipsocode\Cin7\Enums\ContactType;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET me/contacts` — the list envelope is keyed `MeContactsList`.
 *
 * @extends ListRequest<list<MeContactData>>
 */
final class GetMeContacts extends ListRequest
{
    protected string $listKey = 'MeContactsList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?ContactType $type = null,
        protected readonly ?bool $defaultForType = null,
        protected readonly ?string $phone = null,
        protected readonly ?string $fax = null,
        protected readonly ?string $email = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'me/contacts';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Type' => $this->type,
            'DefaultForType' => $this->defaultForType,
            'Phone' => $this->phone,
            'Fax' => $this->fax,
            'Email' => $this->email,
        ];
    }

    /**
     * @return list<MeContactData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): MeContactData => MeContactData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
