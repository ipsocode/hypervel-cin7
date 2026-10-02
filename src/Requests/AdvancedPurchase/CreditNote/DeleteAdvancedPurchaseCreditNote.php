<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchaseCreditNotesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE advanced-purchase/creditnote?TaskID`, voids a credit note; the response is the
 * purchase's credit notes. Unlike the other credit note deletes, the reference documents no `Void`
 * flag: this one only voids.
 *
 * @extends Cin7Request<AdvancedPurchaseCreditNotesData>
 */
final class DeleteAdvancedPurchaseCreditNote extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/creditnote';
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

    public function createDtoFromResponse(Response $response): AdvancedPurchaseCreditNotesData
    {
        return AdvancedPurchaseCreditNotesData::from($response->json())->setResponse($response);
    }
}
