<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields of the Disassembly table that the `disassembly` response and its POST body share,
 * optional in each: the response requires none of them, and the POST body adds the `Quantity`,
 * `Status` and `WIPAccount` its table requires.
 *
 * @see docs/data.md
 */
abstract class AbstractDisassemblyData extends Data
{
    public ?string $ProductName = null;

    #[DateTime]
    public ?string $CompletionDate = null;
}
