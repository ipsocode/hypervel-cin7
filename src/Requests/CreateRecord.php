<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Traits\Body\HasJsonBody;
use Ipsocode\Cin7\Endpoint;

/**
 * Creates a record from a raw JSON body.
 *
 * Writes never carry the page/limit defaults.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
final class CreateRecord extends Cin7Request
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(Endpoint $endpoint, protected readonly array $data = [])
    {
        parent::__construct($endpoint);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
