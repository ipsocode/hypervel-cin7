<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyTask;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT moneyOperation`, body is a `MoneyTaskPutData` and carries `TaskID`; the response is the
 * saved Money Task.
 *
 * @extends WriteRequest<MoneyTaskData>
 */
final class PutMoneyTask extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = MoneyTaskData::class;

    public function resolveEndpoint(): string
    {
        return 'moneyOperation';
    }
}
