<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Journal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Journal\JournalData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE journal?ID&Void`, voids or undoes a journal; the response is the list envelope holding
 * the journal.
 *
 * @extends Cin7Request<JournalData>
 */
final class DeleteJournal extends Cin7Request
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
        return 'journal';
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

    public function createDtoFromResponse(Response $response): JournalData
    {
        return JournalData::from($response->json('Journals.0'))->setResponse($response);
    }
}
