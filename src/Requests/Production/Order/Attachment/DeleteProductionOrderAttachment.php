<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Attachment;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE production/order/attachment?ProductionOrderAttachmentID`, deletes an attachment; the
 * response is not documented.
 *
 * @extends Cin7Request<null>
 */
final class DeleteProductionOrderAttachment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $productionOrderAttachmentId,
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
            'ProductionOrderAttachmentID' => $this->productionOrderAttachmentId,
        ]);
    }
}
