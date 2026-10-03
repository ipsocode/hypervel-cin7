<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\ManualJournal;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/manualJournal?ProductionOrderID`, body is a
 * `ProductionRunManualJournalsPutData`; the response is the production order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PutProductionOrderRunManualJournal extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(
        array|Data $body,
        protected readonly string $productionOrderId,
    ) {
        parent::__construct($body);
    }

    public function resolveEndpoint(): string
    {
        return 'production/order/run/manualJournal';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductionOrderID' => $this->productionOrderId,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionRunsData
    {
        return ProductionRunsData::from($response->json())->setResponse($response);
    }
}
