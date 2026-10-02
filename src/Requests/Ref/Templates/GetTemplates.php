<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Templates;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Templates\TemplateData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/templates` — the list envelope is keyed `Templates` and carries no `Total`.
 *
 * @extends ListRequest<list<TemplateData>>
 */
final class GetTemplates extends ListRequest
{
    protected string $listKey = 'Templates';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $type = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/templates';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Type' => $this->type,
            'Name' => $this->name,
        ];
    }

    /**
     * @return list<TemplateData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TemplateData => TemplateData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
