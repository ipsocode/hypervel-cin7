<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\CreditNote\SaleCreditNotePostData;
use Ipsocode\Cin7\Requests\Sale\CreditNote\DeleteSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\GetSaleCreditNote;
use Ipsocode\Cin7\Requests\Sale\CreditNote\PostSaleCreditNote;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class CreditNoteResource extends BaseResource
{
    /**
     * A sale's credit notes.
     *
     * @param null|bool $combineAdditionalCharges list the additional charges in `Lines`
     * @param null|bool $includeProductInfo add the products the lines use
     * @param null|bool $includePaymentInfo add each credit note's payments and balance
     */
    public function get(
        string $saleId,
        ?bool $combineAdditionalCharges = null,
        ?bool $includeProductInfo = null,
        ?bool $includePaymentInfo = null,
    ): Response {
        return $this->connector->send(new GetSaleCreditNote(
            $saleId,
            $combineAdditionalCharges,
            $includeProductInfo,
            $includePaymentInfo,
        ));
    }

    /**
     * @param array<string, mixed>|SaleCreditNotePostData $body
     */
    public function post(array|SaleCreditNotePostData $body): Response
    {
        return $this->connector->send(new PostSaleCreditNote($body));
    }

    /**
     * Void the credit note (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(
        string $taskId,
        ?bool $void = null,
    ): Response {
        return $this->connector->send(new DeleteSaleCreditNote($taskId, $void));
    }
}
