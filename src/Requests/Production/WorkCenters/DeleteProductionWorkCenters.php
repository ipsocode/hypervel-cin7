<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE production/workcenters?WorkCenterId`, deletes a work center; the response is not
 * documented.
 *
 * @extends Cin7Request<null>
 */
final class DeleteProductionWorkCenters extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $workCenterId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'WorkCenterId' => $this->workCenterId,
        ]);
    }
}
