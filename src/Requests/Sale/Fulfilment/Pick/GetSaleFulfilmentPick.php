<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/fulfilment/pick?TaskID`, a fulfilment's pick. Optional parameter:
 * `IncludeProductInfo`.
 *
 * @extends Cin7Request<SaleFulfilmentPickData>
 */
final class GetSaleFulfilmentPick extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $includeProductInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/pick';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'IncludeProductInfo' => $this->includeProductInfo,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentPickData
    {
        return SaleFulfilmentPickData::from($response->json())->setResponse($response);
    }
}
