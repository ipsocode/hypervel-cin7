<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderPostData;
use Ipsocode\Cin7\Requests\Purchase\Order\GetPurchaseOrder;
use Ipsocode\Cin7\Requests\Purchase\Order\PostPurchaseOrder;

/**
 * `purchase/order`, a purchase's order.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * A purchase's order, by its `TaskID`.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $taskId,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetPurchaseOrder($taskId, $combineAdditionalCharges));
    }

    /**
     * @param array<string, mixed>|PurchaseOrderPostData $body
     */
    public function post(array|PurchaseOrderPostData $body): Response
    {
        return $this->connector->send(new PostPurchaseOrder($body));
    }
}
