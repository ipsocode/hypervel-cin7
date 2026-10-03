<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Journal\JournalPostData;
use Ipsocode\Cin7\Data\Journal\JournalPutData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Journal\DeleteJournal;
use Ipsocode\Cin7\Requests\Journal\GetJournal;
use Ipsocode\Cin7\Requests\Journal\PostJournal;
use Ipsocode\Cin7\Requests\Journal\PutJournal;

/**
 * `journal`, the manual journals.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class JournalResource extends BaseResource
{
    /**
     * One page of journals; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $taskId only the journal with this task ID
     * @param null|CompletionStatus $status only journals with this status
     * @param null|string $search only journals with this text in their number, status, narration or notes
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $taskId = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
    ): Response {
        return $this->connector->send(new GetJournal($page, $limit, $taskId, $status, $search));
    }

    /**
     * Every page of journals, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $taskId only the journal with this task ID
     * @param null|CompletionStatus $status only journals with this status
     * @param null|string $search only journals with this text in their number, status, narration or notes
     */
    public function paginate(
        ?int $limit = null,
        ?string $taskId = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetJournal(null, $limit, $taskId, $status, $search));
    }

    /**
     * @param array<string, mixed>|JournalPostData $body
     */
    public function post(array|JournalPostData $body): Response
    {
        return $this->connector->send(new PostJournal($body));
    }

    /**
     * @param array<string, mixed>|JournalPutData $body
     */
    public function put(array|JournalPutData $body): Response
    {
        return $this->connector->send(new PutJournal($body));
    }

    /**
     * Void a journal with `void: true`, or undo the void with `void: false`; without `void` none
     * is sent and Cin7 defaults it to `false`.
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteJournal($id, $void));
    }
}
