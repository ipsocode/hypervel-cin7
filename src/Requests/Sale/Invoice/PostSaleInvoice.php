<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/invoice`, body needs `SaleID` and an empty-GUID `TaskID`; the response is the sale's invoices.
 *
 * @extends WriteRequest<SaleInvoicesData>
 */
final class PostSaleInvoice extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
    }

    public function createDtoFromResponse(Response $response): SaleInvoicesData
    {
        return SaleInvoicesData::from($response->json())->setResponse($response);
    }
}
