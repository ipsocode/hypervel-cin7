<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\FactoryCalendar;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\FactoryCalendar\FactoryCalendarData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/factoryCalendar?Year`, the factory calendar of a year.
 *
 * @extends Cin7Request<FactoryCalendarData>
 */
final class GetProductionFactoryCalendar extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = FactoryCalendarData::class;

    public function __construct(
        protected readonly int $year,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/factoryCalendar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'Year' => $this->year,
        ]);
    }
}
