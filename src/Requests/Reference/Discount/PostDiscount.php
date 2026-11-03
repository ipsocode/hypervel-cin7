<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\Discount;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRuleData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST reference/discount`, body is a `ProductDiscountRulesPostData`; the response is the saved rule, in `DiscountRules`.
 *
 * @extends WriteRequest<ProductDiscountRuleData>
 */
final class PostDiscount extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = ProductDiscountRuleData::class;

    protected ?string $dtoPath = 'DiscountRules.0';

    public function resolveEndpoint(): string
    {
        return 'reference/discount';
    }
}
