<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Attachment;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SaleAttachmentsData::class;

    public function __construct(
        protected readonly string $saleId,
    ) {
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
}
