<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyTask;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\MoneyTask\MoneyTaskData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE moneyOperation?ID&Void`, voids or undoes a Money Task; the response is the Money Task.
 *
 * @extends Cin7Request<MoneyTaskData>
 */
final class DeleteMoneyTask extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = MoneyTaskData::class;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
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
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }
}
