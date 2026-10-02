<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Pagination;

use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\PagedPaginator;
use Ipsocode\Cin7\PageDefaults;

/**
 * Page pagination over Cin7's `{Total, Page, <list key>}` envelope.
 *
 * Cin7 never echoes the limit back, so the last page comes from `Total` over the limit sent.
 *
 * @see docs/pagination.md
 */
class Cin7Paginator extends PagedPaginator
{
    /**
     * Apply the page number and, when set, the page limit to the request.
     */
    protected function applyPagination(Request $request): Request
    {
        $parameters = ['page' => $this->pageNumber];

        if ($this->perPageLimit !== null) {
            $parameters['limit'] = $this->perPageLimit;
        }

        return $request->withQueryParameters($parameters);
    }

    protected function isLastPage(Response $response): bool
    {
        return $this->servedPageNumber($response) >= $this->getTotalPages($response);
    }

    /**
     * The fallback for a request that does not map its own items: the envelope's `<Thing>List`,
     * found by suffix as `Errors`/`Warnings` are arrays too.
     *
     * @return array<array-key, mixed>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        foreach ((array) $response->json() as $key => $value) {
            if (is_array($value) && is_string($key) && str_ends_with($key, 'List')) {
                return $value;
            }
        }

        return [];
    }

    protected function getTotalPages(Response $response): int
    {
        $total = (int) $response->json('Total', 0);
        // The limit sent on the wire: a caller can set `limit` without calling perPageLimit().
        $limit = (int) ($response->pendingRequest()->queryParameters()['limit'] ?? PageDefaults::LIMIT);

        return $total > 0 && $limit > 0 ? (int) ceil($total / $limit) : 1;
    }

    /**
     * The page number Cin7 says it served, falling back to the one requested.
     */
    private function servedPageNumber(Response $response): int
    {
        return (int) $response->json('Page', $this->pageNumber);
    }
}
