<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly\Order;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Disassembly\Order\DisassemblyOrderData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST disassembly/order`, body is a `DisassemblyOrderData`; the response is the saved
 * disassembly order.
 *
 * @extends WriteRequest<DisassemblyOrderData>
 */
final class PostDisassemblyOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = DisassemblyOrderData::class;

    public function resolveEndpoint(): string
    {
        return 'disassembly/order';
    }
}
