<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayPostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway\GetAdvancedPurchasePutAway;
use Ipsocode\Cin7\Requests\AdvancedPurchase\PutAway\PostAdvancedPurchasePutAway;

/**
 * `advanced-purchase/put-away`, an advanced purchase's put away.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PutAwayResource extends BaseResource
{
    /**
     * An advanced purchase's put away tasks, by its `PurchaseID`.
     */
    public function get(
        string $purchaseId,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchasePutAway($purchaseId));
    }

    /**
     * @param AdvancedPurchasePutAwayPostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePutAwayPostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchasePutAway($body));
    }
}
