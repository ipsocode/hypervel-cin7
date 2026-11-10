<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE disassembly?ID&Void`, voids or undoes a disassembly; the response is the disassembly.
 *
 * @extends Cin7Request<DisassemblyData>
 */
final class DeleteDisassembly extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = DisassemblyData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
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
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }
}
