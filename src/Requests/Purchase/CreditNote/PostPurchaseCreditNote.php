<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNoteData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/creditnote`, body is a `PurchaseCreditNotePostData` or an array; the response is
 * the saved credit note. Cin7 rejects it unless the invoice is `AUTHORISED` or `PAID` and the
 * credit note `DRAFT` or `NOT AVAILABLE`. An `Unstock` line's `ProductID`, `SKU`, `Name`,
 * `Location`, `BatchSN` and `ExpiryDate` are read-only, so they are left out of the body.
 *
 * @extends WriteRequest<PurchaseCreditNoteData>
 */
final class PostPurchaseCreditNote extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = [
        'Unstock.*.ProductID',
        'Unstock.*.SKU',
        'Unstock.*.Name',
        'Unstock.*.Location',
        'Unstock.*.BatchSN',
        'Unstock.*.ExpiryDate',
    ];

    public function resolveEndpoint(): string
    {
        return 'purchase/creditnote';
    }

    public function createDtoFromResponse(Response $response): PurchaseCreditNoteData
    {
        return PurchaseCreditNoteData::from($response->json())->setResponse($response);
    }
}
