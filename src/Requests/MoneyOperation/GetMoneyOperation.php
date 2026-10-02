<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyOperation;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Requests\KeyedRequest;

/**
 * `GET moneyOperation?TaskID`, one Money Task. V2 marks `TaskID` optional, but the list lives
 * at `moneyTaskList`, so it is required here.
 *
 * @extends KeyedRequest<MoneyTaskData>
 */
final class GetMoneyOperation extends KeyedRequest
{
    protected string $idKey = 'TaskID';

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'moneyOperation';
    }

    public function createDtoFromResponse(Response $response): MoneyTaskData
    {
        return MoneyTaskData::from($response->json())->setResponse($response);
    }
}
