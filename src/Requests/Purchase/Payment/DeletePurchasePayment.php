<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE purchase/payment?ID&DeleteAllocation`, removes one payment and, unless
 * `DeleteAllocation` is `false`, its allocated payments; the response `{Success}` is left to
 * `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeletePurchasePayment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $deleteAllocation = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/payment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'DeleteAllocation' => $this->deleteAllocation,
        ]);
    }
}
