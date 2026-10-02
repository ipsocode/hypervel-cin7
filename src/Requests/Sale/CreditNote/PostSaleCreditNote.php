<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/creditnote`, body is a Sale Credit Note; the response is the sale's credit notes.
 *
 * @extends WriteRequest<SaleCreditNotesData>
 */
final class PostSaleCreditNote extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/creditnote';
    }

    public function createDtoFromResponse(Response $response): SaleCreditNotesData
    {
        return SaleCreditNotesData::from($response->json())->setResponse($response);
    }
}
