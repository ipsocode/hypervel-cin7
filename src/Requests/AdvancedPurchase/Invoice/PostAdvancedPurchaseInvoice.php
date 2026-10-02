<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoicesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase/invoice`, body is an `AdvancedPurchasePartialInvoicePostData` or an array;
 * it saves the invoice task it names, and the response is the purchase's invoices. Cin7 rejects it
 * unless the order is `AUTHORISED` and the invoice `DRAFT` or `NOT AVAILABLE`, and, for a purchase
 * whose `Approach` is `STOCK`, the stock received is `AUTHORISED`.
 *
 * @extends WriteRequest<AdvancedPurchaseInvoicesData>
 */
final class PostAdvancedPurchaseInvoice extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/invoice';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseInvoicesData
    {
        return AdvancedPurchaseInvoicesData::from($response->json())->setResponse($response);
    }
}
