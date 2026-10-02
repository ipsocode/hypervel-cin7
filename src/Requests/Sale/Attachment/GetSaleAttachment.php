<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Attachment\SaleAttachmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET sale/attachment?SaleID`, a sale's attachments.
 *
 * @extends Cin7Request<SaleAttachmentsData>
 */
final class GetSaleAttachment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
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
            'SaleID' => $this->saleId,
        ]);
    }

    public function createDtoFromResponse(Response $response): SaleAttachmentsData
    {
        return SaleAttachmentsData::from($response->json())->setResponse($response);
    }
}
