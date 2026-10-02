<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyTask;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT moneyOperation`, body is a Money Task and carries `TaskID`; the response is the saved Money Task.
 *
 * @extends WriteRequest<MoneyTaskData>
 */
final class PutMoneyTask extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'moneyOperation';
    }

    public function createDtoFromResponse(Response $response): MoneyTaskData
    {
        return MoneyTaskData::from($response->json())->setResponse($response);
    }
}
