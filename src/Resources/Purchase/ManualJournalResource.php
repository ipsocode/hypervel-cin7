<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalPostData;
use Ipsocode\Cin7\Requests\Purchase\ManualJournal\GetPurchaseManualJournal;
use Ipsocode\Cin7\Requests\Purchase\ManualJournal\PostPurchaseManualJournal;

/**
 * `purchase/manualJournal`, a purchase's manual journal. The reference marks it deprecated: it
 * supports only simple purchases, and an advanced purchase's manual journals are on
 * `advanced-purchase/manualJournal`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ManualJournalResource extends BaseResource
{
    /**
     * A purchase's manual journal, by its `TaskID`.
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetPurchaseManualJournal($taskId));
    }

    /**
     * @param array<string, mixed>|PurchaseManualJournalPostData $body
     */
    public function post(array|PurchaseManualJournalPostData $body): Response
    {
        return $this->connector->send(new PostPurchaseManualJournal($body));
    }
}
