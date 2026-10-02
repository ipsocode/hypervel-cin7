<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNotesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase/creditnote`, body is an `AdvancedPurchasePartialCreditNotePostData` or
 * an array; the response is the purchase's credit notes. Cin7 rejects it unless the invoice is
 * `AUTHORISED` or `PAID` and the credit note `DRAFT` or `NOT AVAILABLE`. An `Unstock` line's
 * `ProductID`, `SKU`, `Name`, `Location`, `BatchSN` and `ExpiryDate` are read-only, so they are
 * left out of the body.
 *
 * @extends WriteRequest<AdvancedPurchaseCreditNotesData>
 */
final class PostAdvancedPurchaseCreditNote extends WriteRequest
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
        return 'advanced-purchase/creditnote';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseCreditNotesData
    {
        return AdvancedPurchaseCreditNotesData::from($response->json())->setResponse($response);
    }
}
