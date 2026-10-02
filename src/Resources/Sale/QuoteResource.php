<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Quote\SaleQuotePostData;
use Ipsocode\Cin7\Requests\Sale\Quote\GetSaleQuote;
use Ipsocode\Cin7\Requests\Sale\Quote\PostSaleQuote;

/**
 * `sale/quote`, a sale's quote.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class QuoteResource extends BaseResource
{
    /**
     * A sale's quote.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     * @param null|bool $includeProductInfo add the products the lines use
     */
    public function get(
        string $saleId,
        ?bool $combineAdditionalCharges = null,
        ?bool $includeProductInfo = null,
    ): Response {
        return $this->connector->send(new GetSaleQuote($saleId, $combineAdditionalCharges, $includeProductInfo));
    }

    /**
     * @param array<string, mixed>|SaleQuotePostData $body
     */
    public function post(array|SaleQuotePostData $body): Response
    {
        return $this->connector->send(new PostSaleQuote($body));
    }
}
