<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoiceData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/invoice?TaskID`, a purchase's invoice. Optional parameter:
 * `CombineAdditionalCharges`.
 *
 * @extends Cin7Request<PurchaseInvoiceData>
 */
final class GetPurchaseInvoice extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $combineAdditionalCharges = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/invoice';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseInvoiceData
    {
        return PurchaseInvoiceData::from($response->json())->setResponse($response);
    }
}
