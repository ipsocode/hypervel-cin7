<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\PurchaseCreditNoteList\GetPurchaseCreditNoteList;

/**
 * `purchaseCreditNoteList`, the purchases with a credit note.
 */
final class PurchaseCreditNoteListResource extends ListResource
{
    /**
     * One page of purchases with a credit note; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only purchases with this text in the order number, status,
     *                            supplier, invoice number or credit note number
     * @param null|DateTimeInterface|string $updatedSince only purchases changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only purchases changed before this time
     * @param null|TaskStatus $creditNoteStatus only purchases with this credit note status
     * @param null|string $status only purchases with this status
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $creditNoteStatus = null,
        ?string $status = null,
    ): Response {
        return $this->sendList(new GetPurchaseCreditNoteList(
            $page,
            $limit,
            $search,
            $updatedSince,
            $updatedUntil,
            $creditNoteStatus,
            $status,
        ));
    }

    /**
     * Every page of purchases with a credit note, fetched as they are walked; call `startPage()`
     * on the paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $search only purchases with this text in the order number, status,
     *                            supplier, invoice number or credit note number
     * @param null|DateTimeInterface|string $updatedSince only purchases changed after this time
     * @param null|DateTimeInterface|string $updatedUntil only purchases changed before this time
     * @param null|TaskStatus $creditNoteStatus only purchases with this credit note status
     * @param null|string $status only purchases with this status
     */
    public function paginate(
        ?int $limit = null,
        ?string $search = null,
        DateTimeInterface|string|null $updatedSince = null,
        DateTimeInterface|string|null $updatedUntil = null,
        ?TaskStatus $creditNoteStatus = null,
        ?string $status = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetPurchaseCreditNoteList(
            null,
            $limit,
            $search,
            $updatedSince,
            $updatedUntil,
            $creditNoteStatus,
            $status,
        ));
    }
}
