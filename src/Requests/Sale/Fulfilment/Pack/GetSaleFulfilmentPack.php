<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/fulfilment/pack?TaskID`, a fulfilment's pack. Optional parameter:
 * `IncludeProductInfo`.
 *
 * @extends Cin7Request<SaleFulfilmentPackData>
 */
final class GetSaleFulfilmentPack extends Cin7Request
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
        return 'sale/fulfilment/pack';
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

    public function createDtoFromResponse(Response $response): SaleFulfilmentPackData
    {
        return SaleFulfilmentPackData::from($response->json())->setResponse($response);
    }
}
