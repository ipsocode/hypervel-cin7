<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET sale/payment?SaleID`, a sale's payments; the response is a bare array.
 *
 * @extends KeyedRequest<list<SalePaymentLinePartialData>>
 */
final class GetSalePayment extends KeyedRequest
{
    protected string $idKey = 'SaleID';

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'sale/payment';
    }

    /**
     * @return list<SalePaymentLinePartialData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): SalePaymentLinePartialData => SalePaymentLinePartialData::from($item)->setResponse($response),
            array_values($response->json()),
        );
    }
}
