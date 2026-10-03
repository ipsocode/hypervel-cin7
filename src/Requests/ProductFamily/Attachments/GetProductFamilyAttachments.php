<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductFamily\Attachments;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET productFamily/attachments?FamilyID`, the product family's attachments, a bare list of `AttachmentLineData`. V2 marks `FamilyID` optional, but it names the
 * product family whose attachments are wanted, so it is required.
 *
 * @extends Cin7Request<list<AttachmentLineData>>
 */
final class GetProductFamilyAttachments extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $familyId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'productFamily/attachments';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'FamilyID' => $this->familyId,
        ]);
    }

    /**
     * @return list<AttachmentLineData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(AttachmentLineData::class, $response, $response->json());
    }
}
