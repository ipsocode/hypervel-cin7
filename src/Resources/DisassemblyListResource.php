<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\DisassemblyStatus;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\DisassemblyList\GetDisassemblyList;

/**
 * `disassemblyList`, the disassembly list.
 */
final class DisassemblyListResource extends ListResource
{
    /**
     * One page of disassemblies; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|DisassemblyStatus $status only disassemblies with this status
     * @param null|string $search only disassemblies with this text in the disassembly number, location, status, name or product code
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?DisassemblyStatus $status = null,
        ?string $search = null,
    ): Response {
        return $this->sendList(new GetDisassemblyList(
            $page,
            $limit,
            $status,
            $search,
        ));
    }

    /**
     * Every page of disassemblies, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|DisassemblyStatus $status only disassemblies with this status
     * @param null|string $search only disassemblies with this text in the disassembly number, location, status, name or product code
     */
    public function paginate(
        ?int $limit = null,
        ?DisassemblyStatus $status = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetDisassemblyList(
            null,
            $limit,
            $status,
            $search,
        ));
    }
}
