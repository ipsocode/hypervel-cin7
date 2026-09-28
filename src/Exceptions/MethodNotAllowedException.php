<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Exceptions;

use Hypervel\Saloon\Exceptions\SaloonException;

/**
 * Thrown when a request verb is not supported by its endpoint.
 *
 * Raised while the request is being constructed, so — like the marker
 * interfaces it replaces — no HTTP call is ever made.
 */
class MethodNotAllowedException extends SaloonException
{
}
