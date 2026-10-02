<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE sale/invoice?TaskID&Void`, voids or undoes a void of an invoice; the response is the sale's invoices.
 *
 * @extends Cin7Request<SaleInvoicesData>
 */
final class DeleteSaleInvoice extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $void = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'Void' => $this->void,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleInvoicesData
    {
        return SaleInvoicesData::from($response->json())->setResponse($response);
    }
}
