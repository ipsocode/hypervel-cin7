<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Saloon\Traits\Body\HasJsonBody;

/**
 * Creates or updates a record from a raw JSON body, sent verbatim.
 *
 * The caller supplies the identifier a PUT body needs; writes never carry the
 * page/limit defaults.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
abstract class WriteRequest extends Cin7Request
{
    use HasJsonBody;

    /**
     * @param array<string, mixed> $body
     */
    public function __construct(protected readonly array $body = [])
    {
        parent::__construct();
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->body;
    }
}
