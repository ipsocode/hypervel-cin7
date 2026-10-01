<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Traits\Body\HasJsonBody;
use Ipsocode\Cin7\Endpoint;

/**
 * Updates a record, merging the GUID into the JSON body under the endpoint's `guidKey()`.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
final class UpdateRecord extends Cin7Request
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        Endpoint $endpoint,
        protected readonly string $guid,
        protected readonly array $data = [],
    ) {
        parent::__construct($endpoint);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $data = $this->data;
        $data[$this->endpoint->guidKey()] = $this->guid;

        return $data;
    }
}
