<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `DELETE sale/creditnote?TaskID&Void`, voids or undoes a void of a credit note; the response is the sale's credit notes.
 *
 * @extends KeyedRequest<SaleCreditNotesData>
 */
final class DeleteSaleCreditNote extends KeyedRequest
{
    protected string $idKey = 'TaskID';

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return 'sale/creditnote';
    }

    public function createDtoFromResponse(Response $response): SaleCreditNotesData
    {
        return SaleCreditNotesData::from($response->json())->setResponse($response);
    }
}
