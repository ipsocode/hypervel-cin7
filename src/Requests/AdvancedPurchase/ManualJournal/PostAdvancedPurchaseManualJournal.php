<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase/manualJournal`, body is an `AdvancedPurchasePartialManualJournalPostData`
 * or an array; the response is the purchase's manual journals. A line's `IsSystem` is read-only,
 * so it is left out of the body.
 *
 * @extends WriteRequest<AdvancedPurchaseManualJournalsData>
 */
final class PostAdvancedPurchaseManualJournal extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.IsSystem'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/manualJournal';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseManualJournalsData
    {
        return AdvancedPurchaseManualJournalsData::from($response->json())->setResponse($response);
    }
}
