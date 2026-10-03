<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Concerns;

/**
 * `CustomField1` to `CustomField10`, which a finished goods task, a production order, a production
 * run and its operations and outputs, and their list rows carry. The reference documents them as
 * ten strings with no length (`AbstractOpportunityData` declares its own, which the reference gives
 * 256 characters).
 *
 * @see docs/data.md
 */
trait HasCustomFields
{
    public ?string $CustomField1 = null;

    public ?string $CustomField2 = null;

    public ?string $CustomField3 = null;

    public ?string $CustomField4 = null;

    public ?string $CustomField5 = null;

    public ?string $CustomField6 = null;

    public ?string $CustomField7 = null;

    public ?string $CustomField8 = null;

    public ?string $CustomField9 = null;

    public ?string $CustomField10 = null;
}
