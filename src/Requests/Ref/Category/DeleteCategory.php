<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Category;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE ref/category?ID`, deletes a product category; the response `{Success}` is left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteCategory extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'ref/category';
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
