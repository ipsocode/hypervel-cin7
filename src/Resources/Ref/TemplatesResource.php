<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Templates\GetTemplates;

/**
 * `ref/templates`, the templates.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class TemplatesResource extends BaseResource
{
    /**
     * One page of templates; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $type only templates of this type
     * @param null|string $name only templates with this name
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $type = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetTemplates(
            $page,
            $limit,
            $type,
            $name,
        ));
    }

    /**
     * Every page of templates, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $type only templates of this type
     * @param null|string $name only templates with this name
     */
    public function paginate(
        ?int $limit = null,
        ?string $type = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetTemplates(
            null,
            $limit,
            $type,
            $name,
        ));
    }
}
