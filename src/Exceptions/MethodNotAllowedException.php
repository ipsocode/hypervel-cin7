<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Exceptions;

use Hypervel\Saloon\Exceptions\SaloonException;

/**
 * Thrown when a request verb is not supported by its endpoint.
 *
 * Raised while the request is being constructed, so no HTTP call is made.
 *
 * @see docs/requests.md
 */
class MethodNotAllowedException extends SaloonException
{
}
