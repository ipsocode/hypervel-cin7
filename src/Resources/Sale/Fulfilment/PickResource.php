<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale\Fulfilment;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPutData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\GetSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PostSaleFulfilmentPick;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick\PutSaleFulfilmentPick;

/**
 * `sale/fulfilment/pick`, a fulfilment's pick.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PickResource extends BaseResource
{
    /**
     * A fulfilment's pick.
     *
     * @param null|bool $includeProductInfo add the products the lines use
     */
    public function get(string $taskId, ?bool $includeProductInfo = null): Response
    {
        return $this->connector->send(new GetSaleFulfilmentPick($taskId, $includeProductInfo));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentPickPostData $body
     */
    public function post(array|SaleFulfilmentPickPostData $body): Response
    {
        return $this->connector->send(new PostSaleFulfilmentPick($body));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentPickPutData $body
     */
    public function put(array|SaleFulfilmentPickPutData $body): Response
    {
        return $this->connector->send(new PutSaleFulfilmentPick($body));
    }
}
