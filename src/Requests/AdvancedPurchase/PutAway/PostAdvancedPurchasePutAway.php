<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwaysData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase/put-away`, body is an `AdvancedPurchasePutAwayPostData` or an array; it
 * adds lines to a put away task, or creates one without a `TaskID`, and the response is the
 * purchase's put away tasks. A line's `Name` and `Received` are read-only, so they are left out of
 * the body.
 *
 * @extends WriteRequest<AdvancedPurchasePutAwaysData>
 */
final class PostAdvancedPurchasePutAway extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.Name', 'Lines.*.Received'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/put-away';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchasePutAwaysData
    {
        return AdvancedPurchasePutAwaysData::from($response->json())->setResponse($response);
    }
}
