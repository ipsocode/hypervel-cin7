<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/manualJournal?SaleID`, a sale's manual journal.
 *
 * @extends Cin7Request<SaleManualJournalData>
 */
final class GetSaleManualJournal extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/manualJournal';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'SaleID' => $this->saleId,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleManualJournalData
    {
        return SaleManualJournalData::from($response->json())->setResponse($response);
    }
}
