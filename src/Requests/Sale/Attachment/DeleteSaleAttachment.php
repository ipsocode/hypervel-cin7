<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE sale/attachment?ID`, deletes one of a sale's attachments; the response is the sale's
 * attachments. The reference marks `ID` optional, but a delete names what it deletes, so it is
 * required.
 *
 * @extends Cin7Request<SaleAttachmentsData>
 */
final class DeleteSaleAttachment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/attachment';
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

    public function createDtoFromResponse(Response $response): SaleAttachmentsData
    {
        return SaleAttachmentsData::from($response->json())->setResponse($response);
    }
}
