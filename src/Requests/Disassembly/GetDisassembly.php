<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
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

    public function createDtoFromResponse(Response $response): DisassemblyData
    {
        return DisassemblyData::from($response->json())->setResponse($response);
    }
}
