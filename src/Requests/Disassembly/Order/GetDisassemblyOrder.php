<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
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

    public function createDtoFromResponse(Response $response): DisassemblyOrderData
    {
        return DisassemblyOrderData::from($response->json())->setResponse($response);
    }
}
