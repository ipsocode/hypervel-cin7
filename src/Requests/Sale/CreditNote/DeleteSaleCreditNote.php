<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE sale/creditnote?TaskID&Void`, voids or undoes a void of a credit note; the response is the sale's credit notes.
 *
 * @extends Cin7Request<SaleCreditNotesData>
 */
final class DeleteSaleCreditNote extends Cin7Request
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
        return 'sale/creditnote';
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

    public function createDtoFromResponse(Response $response): SaleCreditNotesData
    {
        return SaleCreditNotesData::from($response->json())->setResponse($response);
    }
}
