<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\ManualJournal;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/manualJournal?TaskID`, a purchase's manual journal.
 *
 * @extends Cin7Request<PurchaseManualJournalData>
 */
final class GetPurchaseManualJournal extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = PurchaseManualJournalData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/manualJournal';
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
}
