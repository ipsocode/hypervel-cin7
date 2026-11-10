<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly\Order;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET disassembly/order?TaskID`, a disassembly's order.
 *
 * @extends Cin7Request<DisassemblyOrderData>
 */
final class GetDisassemblyOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = DisassemblyOrderData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'disassembly/order';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }
}
