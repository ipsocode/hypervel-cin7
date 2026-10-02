<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyOperation;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `DELETE moneyOperation?ID&Void`, voids or undoes a Money Task; the response is the Money Task.
 *
 * @extends KeyedRequest<MoneyTaskData>
 */
final class DeleteMoneyOperation extends KeyedRequest
{
    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return 'moneyOperation';
    }

    public function createDtoFromResponse(Response $response): MoneyTaskData
    {
        return MoneyTaskData::from($response->json())->setResponse($response);
    }
}
