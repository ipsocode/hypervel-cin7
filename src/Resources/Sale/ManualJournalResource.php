<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\ManualJournal\SaleManualJournalPostData;
use Ipsocode\Cin7\Requests\Sale\ManualJournal\GetSaleManualJournal;
use Ipsocode\Cin7\Requests\Sale\ManualJournal\PostSaleManualJournal;

/**
 * `sale/manualJournal`, a sale's manual journal.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ManualJournalResource extends BaseResource
{
    /**
     * A sale's manual journal.
     */
    public function get(string $saleId): Response
    {
        return $this->connector->send(new GetSaleManualJournal($saleId));
    }

    /**
     * @param array<string, mixed>|SaleManualJournalPostData $body
     */
    public function post(array|SaleManualJournalPostData $body): Response
    {
        return $this->connector->send(new PostSaleManualJournal($body));
    }
}
