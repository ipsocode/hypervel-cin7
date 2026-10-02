<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\AttributeSet;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE ref/attributeset?ID`, deletes an attribute set; the response `{Success}` is left to
 * `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteAttributeSet extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'ref/attributeset';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
        ]);
    }
}
