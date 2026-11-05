<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Invoice;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale/invoice`, body is a `SaleInvoicePutData` or an array, and needs `SaleID` and `TaskID`; an empty collection in it deletes the existing records.
 *
 * @extends WriteRequest<SaleInvoicesData>
 */
final class PutSaleInvoice extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = SaleInvoicesData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/invoice';
    }
}
