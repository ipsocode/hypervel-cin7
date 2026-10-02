<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/payment?TaskID`, a purchase's payments; the response is a bare array.
 *
 * @extends Cin7Request<list<PurchasePaymentData>>
 */
final class GetPurchasePayment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/payment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }

    /**
     * @return list<PurchasePaymentData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): PurchasePaymentData => PurchasePaymentData::from($item)->setResponse($response),
            array_values($response->json()),
        );
    }
}
