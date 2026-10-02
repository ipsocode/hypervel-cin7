<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchasePartialManualJournalPostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal\GetAdvancedPurchaseManualJournal;
use Ipsocode\Cin7\Requests\AdvancedPurchase\ManualJournal\PostAdvancedPurchaseManualJournal;

/**
 * `advanced-purchase/manualJournal`, an advanced purchase's manual journals. A POST can be sent
 * even when the journal is authorised; a line Cin7 posted (`IsSystem` `true`) cannot be changed or
 * deleted.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ManualJournalResource extends BaseResource
{
    /**
     * An advanced purchase's manual journals, by its `PurchaseID`.
     */
    public function get(string $purchaseId): Response
    {
        return $this->connector->send(new GetAdvancedPurchaseManualJournal($purchaseId));
    }

    /**
     * @param AdvancedPurchasePartialManualJournalPostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePartialManualJournalPostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchaseManualJournal($body));
    }
}
