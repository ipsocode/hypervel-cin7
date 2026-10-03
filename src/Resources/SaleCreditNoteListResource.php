<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\SaleCreditNoteList\GetSaleCreditNoteList;

/**
 * `saleCreditNoteList`, the sales with a credit note.
 */
final class SaleCreditNoteListResource extends ListResource
{
    /**
     * One page of sales with a credit note; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only sales with this text in the order number, status, customer,
     *                            invoice number, customer reference or credit note number
     * @param null|DateTimeInterface|string $createdSince only sales created after this time
     * @param null|DateTimeInterface|string $updatedSince only sales changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only sales changed before this time
     * @param null|TaskStatus $creditNoteStatus only sales with this credit note status
     * @param null|SaleStatus $status only sales with this status
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $createdSince = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $creditNoteStatus = null,
        ?SaleStatus $status = null,
    ): Response {
        return $this->sendList(new GetSaleCreditNoteList(
            $page,
            $limit,
            $search,
            $createdSince,
            $updatedSince,
            $updatedUntil,
            $creditNoteStatus,
            $status,
        ));
    }

    /**
     * Every page of sales with a credit note, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only sales with this text in the order number, status, customer,
     *                            invoice number, customer reference or credit note number
     * @param null|DateTimeInterface|string $createdSince only sales created after this time
     * @param null|DateTimeInterface|string $updatedSince only sales changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only sales changed before this time
     * @param null|TaskStatus $creditNoteStatus only sales with this credit note status
     * @param null|SaleStatus $status only sales with this status
     */
    public function paginate(
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $createdSince = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $creditNoteStatus = null,
        ?SaleStatus $status = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetSaleCreditNoteList(
            null,
            $limit,
            $search,
            $createdSince,
            $updatedSince,
            $updatedUntil,
            $creditNoteStatus,
            $status,
        ));
    }
}
