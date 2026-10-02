<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyOperation;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\MoneyOperation\MoneyTaskData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET moneyOperation?TaskID`, one Money Task. V2 marks `TaskID` optional, but the list lives
 * at `moneyTaskList`, so it is required here.
 *
 * @extends Cin7Request<MoneyTaskData>
 */
final class GetMoneyOperation extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'moneyOperation';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): MoneyTaskData
    {
        return MoneyTaskData::from($response->json())->setResponse($response);
    }
}
