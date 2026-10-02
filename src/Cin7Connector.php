<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Hypervel\RateLimiter\AdmissionPolicy;
use Hypervel\RateLimiter\Limit;
use Hypervel\Saloon\Http\Connector;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Pagination\Contracts\HasPagination;
use Hypervel\Saloon\RateLimit\Traits\HasRateLimits;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Resources\CustomerResource;
use Ipsocode\Cin7\Resources\MoneyOperationResource;
use Ipsocode\Cin7\Resources\ProductResource;
use Ipsocode\Cin7\Resources\RefResource;
use Ipsocode\Cin7\Resources\SaleListResource;
use Ipsocode\Cin7\Resources\SaleResource;
use UnitEnum;

/**
 * The Cin7 Core connector.
 *
 * It holds only readonly scalars, so one singleton per worker is shared across coroutines.
 *
 * @see docs/connector.md
 */
final class Cin7Connector extends Connector implements HasPagination
{
    use HasRateLimits;

    public function __construct(
        private readonly string $accountId,
        private readonly string $applicationKey,
        private readonly int $rateLimitMax = 60,
        private readonly int $rateLimitPeriod = 60,
        private readonly ?string $rateLimitStore = null,
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return 'https://inventory.dearsystems.com/ExternalApi/v2/';
    }

    public function paginate(Request $request): Cin7Paginator
    {
        return new Cin7Paginator($this, $request);
    }

    /**
     * The `customer` resource.
     */
    public function customer(): CustomerResource
    {
        return new CustomerResource($this);
    }

    /**
     * The `moneyOperation` resource.
     */
    public function moneyOperation(): MoneyOperationResource
    {
        return new MoneyOperationResource($this);
    }

    /**
     * The `product` resource.
     */
    public function product(): ProductResource
    {
        return new ProductResource($this);
    }

    /**
     * The `ref` grouping: `ref()->tax()` and `ref()->customer()->credits()`.
     */
    public function ref(): RefResource
    {
        return new RefResource($this);
    }

    /**
     * The `sale` resource.
     */
    public function sale(): SaleResource
    {
        return new SaleResource($this);
    }

    /**
     * The `saleList` resource.
     */
    public function saleList(): SaleListResource
    {
        return new SaleListResource($this);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => $this->accountId,
            'api-auth-applicationkey' => $this->applicationKey,
        ];
    }

    /**
     * Throttle every call against one window per Cin7 account; a non-positive max or
     * period disables it.
     *
     * @return list<AdmissionPolicy>
     */
    protected function resolveRateLimits(PendingRequest $pendingRequest): array
    {
        if ($this->rateLimitMax <= 0 || $this->rateLimitPeriod <= 0) {
            return [];
        }

        return [
            new Limit($this->rateLimitMax, $this->rateLimitPeriod)->by('cin7:api:' . $this->accountId),
        ];
    }

    /**
     * Null falls back to `saloon.rate_limiter.store`, then to the limiter's default store.
     */
    protected function resolveRateLimitStore(): UnitEnum|string|null
    {
        return $this->rateLimitStore;
    }

    /**
     * Wait for capacity instead of throwing; the wait suspends only the calling coroutine.
     */
    protected function waitForRateLimits(): bool
    {
        return true;
    }

    /**
     * Keyed by account, so a 503 cooldown on one Cin7 account never throttles another.
     */
    protected function resolveRateLimitCooldownKey(PendingRequest $pendingRequest): string
    {
        return self::class . ':' . $this->accountId;
    }

    /**
     * Cin7 throttles with a 503 and no `Retry-After`, which becomes a 5 second cooldown.
     *
     * This shadows the `HasRateLimits` trait method, so `parent::` is fatal;
     * alias the trait method to reuse it.
     */
    protected function resolveRateLimitCooldown(Response $response): ?int
    {
        return $response->status() === 503 ? 5 : null;
    }
}
