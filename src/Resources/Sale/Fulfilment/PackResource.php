<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale\Fulfilment;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackPostData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\GetSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PostSaleFulfilmentPack;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack\PutSaleFulfilmentPack;

/**
 * `sale/fulfilment/pack`, a fulfilment's pack.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PackResource extends BaseResource
{
    /**
     * A fulfilment's pack.
     *
     * @param null|bool $includeProductInfo add the products the lines use
     */
    public function get(string $taskId, ?bool $includeProductInfo = null): Response
    {
        return $this->connector->send(new GetSaleFulfilmentPack($taskId, $includeProductInfo));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentPackPostData $body
     */
    public function post(array|SaleFulfilmentPackPostData $body): Response
    {
        return $this->connector->send(new PostSaleFulfilmentPack($body));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentPackData $body
     */
    public function put(array|SaleFulfilmentPackData $body): Response
    {
        return $this->connector->send(new PutSaleFulfilmentPack($body));
    }
}
