<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE sale?ID&Void`, voids or undoes a sale; the response is the Sale.
 *
 * @extends Cin7Request<SaleData>
 */
final class DeleteSale extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = SaleData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }
}
