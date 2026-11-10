<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET disassembly?TaskID`, one disassembly, with its lines.
 *
 * @extends Cin7Request<DisassemblyData>
 */
final class GetDisassembly extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = DisassemblyData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'disassembly';
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
