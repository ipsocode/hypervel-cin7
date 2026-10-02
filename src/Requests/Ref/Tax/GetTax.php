<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/tax` — the list envelope is keyed `TaxRuleList`.
 *
 * @extends ListRequest<mixed>
 */
final class GetTax extends ListRequest
{
    protected string $listKey = 'TaxRuleList';

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }
}
