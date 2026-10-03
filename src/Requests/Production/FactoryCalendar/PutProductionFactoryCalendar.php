<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\FactoryCalendar;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/factoryCalendar`, body is a `FactoryCalendarPutData`; the response is the saved
 * factory calendar.
 *
 * @extends WriteRequest<FactoryCalendarData>
 */
final class PutProductionFactoryCalendar extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/factoryCalendar';
    }

    public function createDtoFromResponse(Response $response): FactoryCalendarData
    {
        return FactoryCalendarData::from($response->json())->setResponse($response);
    }
}
