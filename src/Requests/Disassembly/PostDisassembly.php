<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Disassembly;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Disassembly\DisassemblyData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST disassembly`, body is a `DisassemblyPostData`; the response is the saved disassembly.
 *
 * @extends WriteRequest<DisassemblyData>
 */
final class PostDisassembly extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'disassembly';
    }

    public function createDtoFromResponse(Response $response): DisassemblyData
    {
        return DisassemblyData::from($response->json())->setResponse($response);
    }
}
