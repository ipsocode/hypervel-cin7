<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/manualJournal`, body is a `PurchaseManualJournalPostData` or an array; the
 * response is the saved manual journal. A line's `IsSystem` is read-only, so it is left out of the
 * body.
 *
 * @extends WriteRequest<PurchaseManualJournalData>
 */
final class PostPurchaseManualJournal extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.IsSystem'];

    public function resolveEndpoint(): string
    {
        return 'purchase/manualJournal';
    }

    public function createDtoFromResponse(Response $response): PurchaseManualJournalData
    {
        return PurchaseManualJournalData::from($response->json())->setResponse($response);
    }
}
