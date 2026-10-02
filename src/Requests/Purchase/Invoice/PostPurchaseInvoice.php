<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/invoice`, body is a `PurchaseInvoicePostData` or an array; the response is the
 * saved invoice. Cin7 rejects it unless the order is `AUTHORISED` and the invoice `DRAFT` or
 * `NOT AVAILABLE`, and, for a purchase whose `Approach` is `STOCK`, the stock received is
 * `AUTHORISED`.
 *
 * @extends WriteRequest<PurchaseInvoiceData>
 */
final class PostPurchaseInvoice extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'purchase/invoice';
    }

    public function createDtoFromResponse(Response $response): PurchaseInvoiceData
    {
        return PurchaseInvoiceData::from($response->json())->setResponse($response);
    }
}
