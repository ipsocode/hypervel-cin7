<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Stock;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStocksData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE advanced-purchase/stock?TaskID&Void`, voids a stock receiving task (`Void` true) or
 * undoes a void (false, the default Cin7 applies); the response is the purchase's stock receiving
 * tasks. Not available for simple purchases.
 *
 * @extends Cin7Request<AdvancedPurchaseStocksData>
 */
final class DeleteAdvancedPurchaseStock extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $void = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/stock';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'Void' => $this->void,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseStocksData
    {
        return AdvancedPurchaseStocksData::from($response->json())->setResponse($response);
    }
}
