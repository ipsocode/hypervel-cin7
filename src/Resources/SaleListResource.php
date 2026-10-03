<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\OrderStatus;
use Ipsocode\Cin7\Enums\PackingStatus;
use Ipsocode\Cin7\Enums\PickingStatus;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\ShippingStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;

/**
 * `saleList`, the sales.
 */
final class SaleListResource extends ListResource
{
    /**
     * One page of sales; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only sales with this text in the order number, status, customer,
     *                            invoice number, customer reference or credit note number
     * @param null|DateTimeInterface|string $createdSince only sales created after this time
     * @param null|DateTimeInterface|string $updatedSince only sales changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only sales changed before this time
     * @param null|DateTimeInterface|string $shipBy only sales to ship by this date whose shipment
     *                                              is not authorised
     * @param null|TaskStatus $quoteStatus only sales with this quote status
     * @param null|OrderStatus $orderStatus only sales with this order status
     * @param null|PickingStatus $combinedPickStatus only sales with this combined pick status
     * @param null|PackingStatus $combinedPackStatus only sales with this combined pack status
     * @param null|ShippingStatus $combinedShippingStatus only sales with this combined ship status
     * @param null|string $combinedInvoiceStatus only sales with this combined invoice status
     * @param null|TaskStatus $creditNoteStatus only sales with this credit note status
     * @param null|string $externalId only sales with this external ID
     * @param null|SaleStatus $status only sales with this status
     * @param null|bool $readyForShipping only sales whose pack is authorised and shipment is not
     * @param null|string $orderLocationId only sales ordered from this location
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $createdSince = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        DateTimeInterface|string|null $shipBy = null,
        ?TaskStatus $quoteStatus = null,
        ?OrderStatus $orderStatus = null,
        ?PickingStatus $combinedPickStatus = null,
        ?PackingStatus $combinedPackStatus = null,
        ?ShippingStatus $combinedShippingStatus = null,
        ?string $combinedInvoiceStatus = null,
        ?TaskStatus $creditNoteStatus = null,
        ?string $externalId = null,
        ?SaleStatus $status = null,
        ?bool $readyForShipping = null,
        ?string $orderLocationId = null,
    ): Response {
        return $this->sendList(new GetSaleList(
            $page,
            $limit,
            $search,
            $createdSince,
            $updatedSince,
            $updatedUntil,
            $shipBy,
            $quoteStatus,
            $orderStatus,
            $combinedPickStatus,
            $combinedPackStatus,
            $combinedShippingStatus,
            $combinedInvoiceStatus,
            $creditNoteStatus,
            $externalId,
            $status,
            $readyForShipping,
            $orderLocationId,
        ));
    }

    /**
     * Every page of sales, fetched as they are walked; call `startPage()` on the paginator to begin
     * later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only sales with this text in the order number, status, customer,
     *                            invoice number, customer reference or credit note number
     * @param null|DateTimeInterface|string $createdSince only sales created after this time
     * @param null|DateTimeInterface|string $updatedSince only sales changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only sales changed before this time
     * @param null|DateTimeInterface|string $shipBy only sales to ship by this date whose shipment
     *                                              is not authorised
     * @param null|TaskStatus $quoteStatus only sales with this quote status
     * @param null|OrderStatus $orderStatus only sales with this order status
     * @param null|PickingStatus $combinedPickStatus only sales with this combined pick status
     * @param null|PackingStatus $combinedPackStatus only sales with this combined pack status
     * @param null|ShippingStatus $combinedShippingStatus only sales with this combined ship status
     * @param null|string $combinedInvoiceStatus only sales with this combined invoice status
     * @param null|TaskStatus $creditNoteStatus only sales with this credit note status
     * @param null|string $externalId only sales with this external ID
     * @param null|SaleStatus $status only sales with this status
     * @param null|bool $readyForShipping only sales whose pack is authorised and shipment is not
     * @param null|string $orderLocationId only sales ordered from this location
     */
    public function paginate(
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $createdSince = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        DateTimeInterface|string|null $shipBy = null,
        ?TaskStatus $quoteStatus = null,
        ?OrderStatus $orderStatus = null,
        ?PickingStatus $combinedPickStatus = null,
        ?PackingStatus $combinedPackStatus = null,
        ?ShippingStatus $combinedShippingStatus = null,
        ?string $combinedInvoiceStatus = null,
        ?TaskStatus $creditNoteStatus = null,
        ?string $externalId = null,
        ?SaleStatus $status = null,
        ?bool $readyForShipping = null,
        ?string $orderLocationId = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetSaleList(
            null,
            $limit,
            $search,
            $createdSince,
            $updatedSince,
            $updatedUntil,
            $shipBy,
            $quoteStatus,
            $orderStatus,
            $combinedPickStatus,
            $combinedPackStatus,
            $combinedShippingStatus,
            $combinedInvoiceStatus,
            $creditNoteStatus,
            $externalId,
            $status,
            $readyForShipping,
            $orderLocationId,
        ));
    }
}
