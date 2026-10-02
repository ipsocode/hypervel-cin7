<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotesData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET sale/creditnote?SaleID`, a sale's credit notes. Optional parameters: `CombineAdditionalCharges`, `IncludeProductInfo` and `IncludePaymentInfo`.
 *
 * @extends KeyedRequest<SaleCreditNotesData>
 */
final class GetSaleCreditNote extends KeyedRequest
{
    protected string $idKey = 'SaleID';

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'sale/creditnote';
    }

    public function createDtoFromResponse(Response $response): SaleCreditNotesData
    {
        return SaleCreditNotesData::from($response->json())->setResponse($response);
    }
}
