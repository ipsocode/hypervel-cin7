<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Customer\Credits;

use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/customer/credits` — the list envelope is keyed `CustomerCredits` and carries no
 * `Total`, so a page is the last one when it holds fewer items than the limit sent.
 *
 * @extends ListRequest<mixed>
 */
final class GetCustomerCredits extends ListRequest
{
    protected string $listKey = 'CustomerCredits';

    public function resolveEndpoint(): string
    {
        return 'ref/customer/credits';
    }
}
