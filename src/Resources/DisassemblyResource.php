<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyPostData;
use Ipsocode\Cin7\Requests\Disassembly\DeleteDisassembly;
use Ipsocode\Cin7\Requests\Disassembly\GetDisassembly;
use Ipsocode\Cin7\Requests\Disassembly\PostDisassembly;
use Ipsocode\Cin7\Resources\Disassembly\OrderResource;

/**
 * `disassembly`, a disassembly.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class DisassemblyResource extends BaseResource
{
    /**
     * One disassembly, with its lines.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetDisassembly($taskId));
    }

    /**
     * @param array<string, mixed>|DisassemblyPostData $body
     */
    public function post(array|DisassemblyPostData $body): Response
    {
        return $this->connector->send(new PostDisassembly($body));
    }

    /**
     * Void the disassembly (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $id, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteDisassembly($id, $void));
    }

    /**
     * The `disassembly/order` resource, a disassembly's order.
     */
    public function order(): OrderResource
    {
        return new OrderResource($this->connector);
    }
}
