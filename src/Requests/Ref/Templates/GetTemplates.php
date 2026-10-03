<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Templates;

use Ipsocode\Cin7\Data\Ref\Templates\TemplateData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/templates` — the list envelope is keyed `Templates` and carries no `Total`.
 *
 * @extends ListRequest<TemplateData>
 */
final class GetTemplates extends ListRequest
{
    protected string $listKey = 'Templates';

    protected string $item = TemplateData::class;

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
}
