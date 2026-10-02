<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function resolveEndpoint(): string
    {
        return 'sale/manualJournal';
    }

    public function createDtoFromResponse(Response $response): SaleManualJournalData
    {
        return SaleManualJournalData::from($response->json())->setResponse($response);
    }
}
