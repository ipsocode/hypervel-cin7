<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE stockTransfer?ID&Void`, voids or undoes a stock transfer; the response is the stock transfer.
 *
 * @extends Cin7Request<StockTransferData>
 */
final class DeleteStockTransfer extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'stockTransfer';
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

    public function createDtoFromResponse(Response $response): StockTransferData
    {
        return StockTransferData::from($response->json())->setResponse($response);
    }
}
