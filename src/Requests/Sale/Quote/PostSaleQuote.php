<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Quote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuoteData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/quote`, body is a `SaleQuotePostData`; the response is the saved quote. Cin7 rejects
 * it when the quote is neither `DRAFT` nor `NOT AVAILABLE`, or the sale skips its quote.
 *
 * @extends WriteRequest<SaleQuoteData>
 */
final class PostSaleQuote extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/quote';
    }

    public function createDtoFromResponse(Response $response): SaleQuoteData
    {
        return SaleQuoteData::from($response->json())->setResponse($response);
    }
}
