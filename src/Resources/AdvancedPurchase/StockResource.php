<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPostData;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPutData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\DeleteAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\GetAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PostAdvancedPurchaseStock;
use Ipsocode\Cin7\Requests\AdvancedPurchase\Stock\PutAdvancedPurchaseStock;

/**
 * `advanced-purchase/stock`, an advanced purchase's stock received. It is not available for a
 * service purchase, and only when `Use Put Away` is set in the General Settings; a POST or PUT for
 * a simple purchase converts it to an advanced one.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class StockResource extends BaseResource
{
    /**
     * An advanced purchase's stock receiving tasks, by its `PurchaseID`.
     */
    public function get(
        string $purchaseId,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchaseStock($purchaseId));
    }

    /**
     * @param AdvancedPurchaseStockPostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchaseStockPostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchaseStock($body));
    }

    /**
     * @param AdvancedPurchaseStockPutData|array<string, mixed> $body
     */
    public function put(array|AdvancedPurchaseStockPutData $body): Response
    {
        return $this->connector->send(new PutAdvancedPurchaseStock($body));
    }

    /**
     * Void the stock receiving task (`void: true`), or undo a void (`false`, the default Cin7
     * applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(
        string $taskId,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteAdvancedPurchaseStock($taskId, $void));
    }
}
