<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchaseManualJournalsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/manualJournal?PurchaseID`, an advanced purchase's manual journals.
 *
 * @extends Cin7Request<AdvancedPurchaseManualJournalsData>
 */
final class GetAdvancedPurchaseManualJournal extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $purchaseId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/manualJournal';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'PurchaseID' => $this->purchaseId,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseManualJournalsData
    {
        return AdvancedPurchaseManualJournalsData::from($response->json())->setResponse($response);
    }
}
