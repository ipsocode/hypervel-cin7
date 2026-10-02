<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Account;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE ref/account?Code`, deletes an account; the response `{Success}` is left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteAccount extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $code,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'ref/account';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'Code' => $this->code,
        ]);
    }
}
