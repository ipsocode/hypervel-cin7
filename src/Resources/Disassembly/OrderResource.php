<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Disassembly;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderData;
use Ipsocode\Cin7\Requests\Disassembly\Order\GetDisassemblyOrder;
use Ipsocode\Cin7\Requests\Disassembly\Order\PostDisassemblyOrder;

/**
 * `disassembly/order`, a disassembly order.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * A disassembly's order.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetDisassemblyOrder($taskId));
    }

    /**
     * @param array<string, mixed>|DisassemblyOrderData $body
     */
    public function post(array|DisassemblyOrderData $body): Response
    {
        return $this->connector->send(new PostDisassemblyOrder($body));
    }
}
