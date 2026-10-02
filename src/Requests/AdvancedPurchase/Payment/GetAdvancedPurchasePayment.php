<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/payment?PurchaseID&OrderNumber&InvoiceNumber&CreditNoteNumber`, an
 * advanced purchase's payments, found by the purchase or by its order, invoice or credit note
 * number; the response is a bare array.
 *
 * @extends Cin7Request<list<AdvancedPurchasePaymentData>>
 */
final class GetAdvancedPurchasePayment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly ?string $purchaseId = null,
        protected readonly ?string $orderNumber = null,
        protected readonly ?string $invoiceNumber = null,
        protected readonly ?string $creditNoteNumber = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/payment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'PurchaseID' => $this->purchaseId,
            'OrderNumber' => $this->orderNumber,
            'InvoiceNumber' => $this->invoiceNumber,
            'CreditNoteNumber' => $this->creditNoteNumber,
        ]);
    }

    /**
     * @return list<AdvancedPurchasePaymentData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): AdvancedPurchasePaymentData => AdvancedPurchasePaymentData::from($item)->setResponse($response),
            array_values($response->json()),
        );
    }
}
