<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Attachment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/attachment?TaskID`, a purchase's attachments. The reference's parameter note says
 * it returns payment info, copied from `purchase/payment`; the examples return the attachments.
 *
 * @extends Cin7Request<PurchaseAttachmentsData>
 */
final class GetPurchaseAttachment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
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
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): PurchaseAttachmentsData
    {
        return PurchaseAttachmentsData::from($response->json())->setResponse($response);
    }
}
