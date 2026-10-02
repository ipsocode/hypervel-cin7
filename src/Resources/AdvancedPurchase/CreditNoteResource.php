<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\AdvancedPurchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchasePartialCreditNotePostData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\DeleteAdvancedPurchaseCreditNote;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\GetAdvancedPurchaseCreditNote;
use Ipsocode\Cin7\Requests\AdvancedPurchase\CreditNote\PostAdvancedPurchaseCreditNote;

/**
 * `advanced-purchase/creditnote`, an advanced purchase's credit notes.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CreditNoteResource extends BaseResource
{
    /**
     * An advanced purchase's credit notes, by its `PurchaseID`.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $purchaseId,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetAdvancedPurchaseCreditNote($purchaseId, $combineAdditionalCharges));
    }

    /**
     * @param AdvancedPurchasePartialCreditNotePostData|array<string, mixed> $body
     */
    public function post(array|AdvancedPurchasePartialCreditNotePostData $body): Response
    {
        return $this->connector->send(new PostAdvancedPurchaseCreditNote($body));
    }

    /**
     * Void the credit note.
     */
    public function delete(
        string $taskId,
    ): Response {
        return $this->connector->send(new DeleteAdvancedPurchaseCreditNote($taskId));
    }
}
