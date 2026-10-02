<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Invoice;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchaseInvoicesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE advanced-purchase/invoice?TaskID&Void`, voids an invoice task (`Void` true) or undoes it
 * (false, the default Cin7 applies); the response is the purchase's invoices. Not available for
 * simple purchases.
 *
 * @extends Cin7Request<AdvancedPurchaseInvoicesData>
 */
final class DeleteAdvancedPurchaseInvoice extends Cin7Request
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
        return 'advanced-purchase/invoice';
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

    public function createDtoFromResponse(Response $response): AdvancedPurchaseInvoicesData
    {
        return AdvancedPurchaseInvoicesData::from($response->json())->setResponse($response);
    }
}
