<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SaleCreditNotesData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/creditnote';
    }
}
