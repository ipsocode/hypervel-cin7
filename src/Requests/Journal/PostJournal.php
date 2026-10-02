<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Journal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Journal\JournalData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST journal`, body is a `JournalPostData`; the response is the list envelope holding the saved
 * journal.
 *
 * @extends WriteRequest<JournalData>
 */
final class PostJournal extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'journal';
    }

    public function createDtoFromResponse(Response $response): JournalData
    {
        return JournalData::from($response->json('Journals.0'))->setResponse($response);
    }
}
