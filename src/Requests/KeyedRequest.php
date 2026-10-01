<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

/**
 * Finds or deletes one record by an identifier sent as a query parameter.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
abstract class KeyedRequest extends Cin7Request
{
    /**
     * The query key the identifier is sent under, e.g. `ID` or `SaleID`.
     */
    protected string $idKey = 'ID';

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        protected readonly string $id,
        protected readonly array $parameters = [],
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        // The identifier comes first so it wins over a caller value under the same key.
        return [$this->idKey => $this->id] + $this->queryValues($this->parameters);
    }
}
