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
     * @param array<string, mixed> $parameters
     */
    public function get(string $saleId, array $parameters = []): Response
    {
        return $this->connector->send(new GetSaleCreditNote($saleId, $parameters));
    }

    /**
     * @param array<string, mixed>|SaleCreditNotePostData $body
     */
    public function post(array|SaleCreditNotePostData $body): Response
    {
        return $this->connector->send(new PostSaleCreditNote($body));
    }

    /**
     * Void the credit note (`$void = true`), or undo a void.
     */
    public function delete(string $taskId, bool $void = false): Response
    {
        return $this->connector->send(new DeleteSaleCreditNote($taskId, ['Void' => $void]));
    }
}
