<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\Discount;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function resolveEndpoint(): string
    {
        return 'reference/discount';
    }

    public function createDtoFromResponse(Response $response): ProductDiscountRuleData
    {
        return ProductDiscountRuleData::from($response->json('DiscountRules.0'))->setResponse($response);
    }
}
