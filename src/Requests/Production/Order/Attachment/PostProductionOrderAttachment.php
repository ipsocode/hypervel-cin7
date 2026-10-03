<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Attachment;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrderAttachmentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/attachment?ProductionOrderID`, body is a `ProductionOrderAttachmentData`;
 * the response is the saved attachment.
 *
 * @extends WriteRequest<ProductionOrderAttachmentData>
 */
final class PostProductionOrderAttachment extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(
        array|Data $body,
        protected readonly string $productionOrderId,
    ) {
        parent::__construct($body);
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
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionOrderAttachmentData
    {
        return ProductionOrderAttachmentData::from($response->json())->setResponse($response);
    }
}
