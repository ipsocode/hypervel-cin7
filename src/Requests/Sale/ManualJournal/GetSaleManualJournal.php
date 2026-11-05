<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\ManualJournal;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SaleManualJournalData::class;

    public function __construct(
        protected readonly string $saleId,
    ) {
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
}
