<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;

/**
 * Error Model, the body Cin7 answers a failed call with: `ErrorCode` and the error text in
 * `Exception`. A response carrying it throws a `RequestException`, whatever its status.
 *
 * @see docs/requests.md
 */
final class ErrorData extends Data
{
    public function __construct(
        public ?int $ErrorCode = null,
        public ?string $Exception = null,
    ) {
    }
}
