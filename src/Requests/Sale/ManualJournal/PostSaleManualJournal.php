<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/manualJournal`, body is a `SaleManualJournalPostData`; the response is the saved
 * manual journal.
 *
 * @extends WriteRequest<SaleManualJournalData>
 */
final class PostSaleManualJournal extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = SaleManualJournalData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/manualJournal';
    }
}
