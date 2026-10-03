<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarPostData;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarPutData;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\GetProductionFactoryCalendar;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\PostProductionFactoryCalendar;
use Ipsocode\Cin7\Requests\Production\FactoryCalendar\PutProductionFactoryCalendar;

/**
 * `production/factoryCalendar`, the factoryCalendar resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class FactoryCalendarResource extends BaseResource
{
    /**
     * The factory calendar of a year.
     */
    public function get(int $year): Response
    {
        return $this->connector->send(new GetProductionFactoryCalendar($year));
    }

    /**
     * @param array<string, mixed>|FactoryCalendarPostData $body
     */
    public function post(array|FactoryCalendarPostData $body): Response
    {
        return $this->connector->send(new PostProductionFactoryCalendar($body));
    }

    /**
     * @param array<string, mixed>|FactoryCalendarPutData $body
     */
    public function put(array|FactoryCalendarPutData $body): Response
    {
        return $this->connector->send(new PutProductionFactoryCalendar($body));
    }
}
