<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE sale/fulfilment?TaskID&Void`, voids or undoes a void of a fulfilment of an advanced
 * sale; the response is the sale's fulfilments.
 *
 * @extends Cin7Request<SaleFulfilmentsData>
 */
final class DeleteSaleFulfilment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    protected ?string $dto = SaleFulfilmentsData::class;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $void = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'Void' => $this->void,
        ]);
    }
}
