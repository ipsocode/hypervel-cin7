<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Pagination;

use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\PagedPaginator;
use Ipsocode\Cin7\PageDefaults;

/**
 * Page pagination over Cin7's `{Total, Page, <Thing>List}` envelope.
 *
 * Cin7 reads `page`/`limit` (lowercase) rather than the paginator's default
 * `page`/`per_page`, and the response never echoes the request's own limit
 * back — only `Total` (the full matching record count) and `Page` (the page
 * just served) — so the last page is derived by dividing the two rather than
 * read directly off the body. The item list itself is keyed differently per
 * endpoint (`CustomerList`, `ProductList`, …), so it is found rather than
 * named: every envelope wraps exactly one `<Thing>List` key alongside `Total`
 * and `Page`.
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

    /**
     * Determine if the response is the last page.
     */
    protected function isLastPage(Response $response): bool
    {
        return $this->servedPageNumber($response) >= $this->getTotalPages($response);
    }

    /**
     * Get the items from one page.
     *
     * The envelope's list key varies per endpoint, but it always ends in
     * `List` — unlike an incidental array field such as `Errors` or
     * `Warnings` — so that suffix, not "the first array found", is what
     * identifies it.
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

    /**
     * Get the total number of independently addressable pages.
     *
     * The limit read here has to be the one actually sent on the wire — a
     * caller can set `limit` as a request parameter without ever calling
     * `perPageLimit()`, and dividing by `PageDefaults::LIMIT` in that case
     * would undercount the pages and stop early.
     */
    protected function getTotalPages(Response $response): int
    {
        $total = (int) $response->json('Total', 0);
        $limit = (int) ($response->pendingRequest()->queryParameters()['limit'] ?? PageDefaults::LIMIT);

        return $total > 0 && $limit > 0 ? (int) ceil($total / $limit) : 1;
    }

    /**
     * The page number Cin7 confirmed it served.
     */
    private function servedPageNumber(Response $response): int
    {
        return (int) $response->json('Page', $this->pageNumber);
    }
}
