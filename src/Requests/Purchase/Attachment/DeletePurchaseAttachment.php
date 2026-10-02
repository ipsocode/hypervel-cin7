<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE purchase/attachment?ID`, deletes one of a purchase's attachments; the response is the
 * purchase's attachments. The reference marks `ID` optional, but a delete names what it deletes,
 * so it is required.
 *
 * @extends Cin7Request<PurchaseAttachmentsData>
 */
final class DeletePurchaseAttachment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/attachment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseAttachmentsData
    {
        return PurchaseAttachmentsData::from($response->json())->setResponse($response);
    }
}
