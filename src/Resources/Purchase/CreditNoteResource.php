<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Purchase;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNotePostData;
use Ipsocode\Cin7\Requests\Purchase\CreditNote\GetPurchaseCreditNote;
use Ipsocode\Cin7\Requests\Purchase\CreditNote\PostPurchaseCreditNote;

/**
 * `purchase/creditnote`, a purchase's credit note. The reference marks it deprecated: it supports
 * only simple purchases, and an advanced purchase's credit notes are on
 * `advanced-purchase/creditnote`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CreditNoteResource extends BaseResource
{
    /**
     * A purchase's credit note, by the purchase's `TaskID`.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     */
    public function get(
        string $taskId,
        ?bool $combineAdditionalCharges = null,
    ): Response {
        return $this->connector->send(new GetPurchaseCreditNote($taskId, $combineAdditionalCharges));
    }

    /**
     * @param array<string, mixed>|PurchaseCreditNotePostData $body
     */
    public function post(array|PurchaseCreditNotePostData $body): Response
    {
        return $this->connector->send(new PostPurchaseCreditNote($body));
    }
}
