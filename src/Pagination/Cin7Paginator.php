<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Pagination;

use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\Contracts\MapPaginatedResponseItems;
use Hypervel\Saloon\Pagination\PagedPaginator;
use Ipsocode\Cin7\PageDefaults;
use Ipsocode\Cin7\Requests\ListRequest;
use LogicException;

/**
 * Page pagination over Cin7's `{Total, Page, <list key>}` envelope.
 *
 * Cin7 never echoes the limit back, so the last page comes from `Total` over the limit sent.
 * An envelope with no `Total` (`ref/customer/credits`) ends on a page shorter than that limit.
 *
 * @see src/docs/pagination.md
 *
 * @extends PagedPaginator<mixed>
 */
class Cin7Paginator extends PagedPaginator
{
    /**
     * Apply the page number and, when set, the page limit to the request, within Cin7's bounds:
     * `startPage()` and `perPageLimit()` bypass `PageDefaults::apply()`.
     */
    protected function applyPagination(Request $request): Request
    {
        PageDefaults::ensureValidPage($this->pageNumber);
        $parameters = ['page' => $this->pageNumber];

        if ($this->perPageLimit !== null) {
            PageDefaults::ensureValidLimit($this->perPageLimit);
            $parameters['limit'] = $this->perPageLimit;
        }

        return $request->withQueryParameters($parameters);
    }

    protected function isLastPage(Response $response): bool
    {
        if ($response->json('Total') === null) {
            return count($this->pageItems($response)) < $this->sentLimit($response);
        }

        return $this->servedPageNumber($response) >= $this->getTotalPages($response);
    }

    /**
     * Fail, as Saloon calls this only for a request that does not map its own items: the list key
     * differs per endpoint, so a guess would end the walk after page one with no error.
     *
     * @return array<array-key, mixed>
     *
     * @throws LogicException
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        throw new LogicException(sprintf(
            'The request [%s] cannot be paginated: it must extend [%s] or implement [%s] to map its own items.',
            $request::class,
            ListRequest::class,
            MapPaginatedResponseItems::class,
        ));
    }

    protected function getTotalPages(Response $response): int
    {
        $total = (int) $response->json('Total', 0);
        $limit = $this->sentLimit($response);

        return $total > 0 && $limit > 0 ? (int) ceil($total / $limit) : 1;
    }

    /**
     * The limit sent on the wire: a caller can set `limit` without calling perPageLimit().
     *
     * @param Response<mixed> $response
     */
    private function sentLimit(Response $response): int
    {
        return (int) ($response->pendingRequest()->queryParameters()['limit'] ?? PageDefaults::LIMIT);
    }

    /**
     * The page number Cin7 says it served, falling back to the one requested.
     *
     * @param Response<mixed> $response
     */
    private function servedPageNumber(Response $response): int
    {
        return (int) $response->json('Page', $this->pageNumber);
    }
}
