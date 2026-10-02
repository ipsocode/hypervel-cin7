<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Enums\InvoiceStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\PurchaseList\GetPurchaseList;

/**
 * `purchaseList`, the purchases, simple, advanced and service ones.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PurchaseListResource extends BaseResource
{
    /**
     * One page of purchases; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only purchases with this text in the order number, status,
     *                            supplier, invoice number or credit note number
     * @param null|DateTimeInterface|string $requiredBy only purchases required by this date or
     *                                                  before
     * @param null|DateTimeInterface|string $updatedSince only purchases changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only purchases changed before this time
     * @param null|TaskStatus $orderStatus only purchases with this order status
     * @param null|TaskStatus $restockReceivedStatus only purchases with this stock received (put
     *                                               away) status
     * @param null|InvoiceStatus $invoiceStatus only purchases with this invoice status
     * @param null|TaskStatus $creditNoteStatus only purchases with this credit note status
     * @param null|TaskStatus $unstockStatus only purchases with this unstock status
     * @param null|string $status only purchases with this status
     * @param null|string $dropShipTaskId only the drop-ship purchases this sale task created
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $requiredBy = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $orderStatus = null,
        ?TaskStatus $restockReceivedStatus = null,
        ?InvoiceStatus $invoiceStatus = null,
        ?TaskStatus $creditNoteStatus = null,
        ?TaskStatus $unstockStatus = null,
        ?string $status = null,
        ?string $dropShipTaskId = null,
    ): Response {
        return $this->connector->send(new GetPurchaseList(
            $page,
            $limit,
            $search,
            $requiredBy,
            $updatedSince,
            $updatedUntil,
            $orderStatus,
            $restockReceivedStatus,
            $invoiceStatus,
            $creditNoteStatus,
            $unstockStatus,
            $status,
            $dropShipTaskId,
        ));
    }

    /**
     * Every page of purchases, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only purchases with this text in the order number, status,
     *                            supplier, invoice number or credit note number
     * @param null|DateTimeInterface|string $requiredBy only purchases required by this date or
     *                                                  before
     * @param null|DateTimeInterface|string $updatedSince only purchases changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only purchases changed before this time
     * @param null|TaskStatus $orderStatus only purchases with this order status
     * @param null|TaskStatus $restockReceivedStatus only purchases with this stock received (put
     *                                               away) status
     * @param null|InvoiceStatus $invoiceStatus only purchases with this invoice status
     * @param null|TaskStatus $creditNoteStatus only purchases with this credit note status
     * @param null|TaskStatus $unstockStatus only purchases with this unstock status
     * @param null|string $status only purchases with this status
     * @param null|string $dropShipTaskId only the drop-ship purchases this sale task created
     */
    public function paginate(
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $requiredBy = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $orderStatus = null,
        ?TaskStatus $restockReceivedStatus = null,
        ?InvoiceStatus $invoiceStatus = null,
        ?TaskStatus $creditNoteStatus = null,
        ?TaskStatus $unstockStatus = null,
        ?string $status = null,
        ?string $dropShipTaskId = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetPurchaseList(
            null,
            $limit,
            $search,
            $requiredBy,
            $updatedSince,
            $updatedUntil,
            $orderStatus,
            $restockReceivedStatus,
            $invoiceStatus,
            $creditNoteStatus,
            $unstockStatus,
            $status,
            $dropShipTaskId,
        ));
    }
}
