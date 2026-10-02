<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `DELETE sale/invoice?TaskID&Void`, voids or undoes a void of an invoice; the response is the sale's invoices.
 *
 * @extends KeyedRequest<SaleInvoicesData>
 */
final class DeleteSaleInvoice extends KeyedRequest
{
    protected string $idKey = 'TaskID';

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
    }

    public function createDtoFromResponse(Response $response): SaleInvoicesData
    {
        return SaleInvoicesData::from($response->json())->setResponse($response);
    }
}
