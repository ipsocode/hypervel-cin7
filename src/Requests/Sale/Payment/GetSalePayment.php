<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentLinePartialData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/payment?SaleID`, a sale's payments; the response is a bare array.
 *
 * @extends Cin7Request<list<SalePaymentLinePartialData>>
 */
final class GetSalePayment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/payment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'SaleID' => $this->saleId,
        ]);
    }

    /**
     * @return list<SalePaymentLinePartialData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(SalePaymentLinePartialData::class, $response, $response->json());
    }
}
