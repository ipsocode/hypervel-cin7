<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Webhooks;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE webhooks?ID`, deletes a webhook; the response is an empty `Webhooks` list, left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteWebhooks extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'webhooks';
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
}
