<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNoteData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/creditnote?TaskID`, a purchase's credit note. Optional parameter:
 * `CombineAdditionalCharges`.
 *
 * @extends Cin7Request<PurchaseCreditNoteData>
 */
final class GetPurchaseCreditNote extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
        protected readonly ?bool $combineAdditionalCharges = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/creditnote';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseCreditNoteData
    {
        return PurchaseCreditNoteData::from($response->json())->setResponse($response);
    }
}
