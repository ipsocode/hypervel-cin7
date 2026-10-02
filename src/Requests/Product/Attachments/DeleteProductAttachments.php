<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product\Attachments;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE product/attachments?ID`, deletes one of the product's attachments; the response is the
 * attachments that remain, a bare list of `AttachmentLineData`. The reference marks `ID`
 * optional, but a delete names what it deletes, so it is required.
 *
 * @extends Cin7Request<list<AttachmentLineData>>
 */
final class DeleteProductAttachments extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
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
            'ID' => $this->id,
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
