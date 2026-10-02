<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET sale/invoice?SaleID`, a sale's invoices. Optional parameters: `CombineAdditionalCharges` and `IncludeProductInfo`.
 *
 * @extends KeyedRequest<SaleInvoicesData>
 */
final class GetSaleInvoice extends KeyedRequest
{
    protected string $idKey = 'SaleID';

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
    }

    public function createDtoFromResponse(Response $response): SaleInvoicesData
    {
        return SaleInvoicesData::from($response->json())->setResponse($response);
    }
}
