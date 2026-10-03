<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/order/attachment?ProductionOrderID`, the attachments of a production order, its
 * operations and their resources.
 *
 * @extends Cin7Request<ProductionOrderAttachmentsData>
 */
final class GetProductionOrderAttachment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productionOrderId,
        protected readonly ?bool $returnAttachmentsContent = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/order/attachment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductionOrderID' => $this->productionOrderId,
            'ReturnAttachmentsContent' => $this->returnAttachmentsContent,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionOrderAttachmentsData
    {
        return ProductionOrderAttachmentsData::from($response->json())->setResponse($response);
    }
}
