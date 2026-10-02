<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product\Attachments;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET product/attachments?ProductID`, the product's attachments, a bare list of `AttachmentLineData`. V2 marks `ProductID` optional, but it names the
 * product whose attachments are wanted, so it is required.
 *
 * @extends Cin7Request<list<AttachmentLineData>>
 */
final class GetProductAttachments extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'product/attachments';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductID' => $this->productId,
        ]);
    }

    /**
     * @return list<AttachmentLineData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): AttachmentLineData => AttachmentLineData::from($item)->setResponse($response),
            array_values($response->json()),
        );
    }
}
