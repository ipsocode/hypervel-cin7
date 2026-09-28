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
use Hypervel\Saloon\Pagination\Paginator;
use Hypervel\Saloon\RateLimit\Traits\HasRateLimits;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use UnitEnum;

/**
 * The Cin7 Core connector.
 *
 * Holds nothing but readonly scalars, so a single instance is safe to share
 * across coroutines for the lifetime of a worker — register it as a singleton.
 *
 * Transport comes from the framework's registered `saloon` HTTP connection
 * (connect_timeout 10s, timeout 30s, shared cURL handlers), not a freshly
 * constructed, timeout-free Guzzle client per call.
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

    /**
     * Paginate a request.
     *
     * Only `ListRecords` implements `Paginatable`, so this is the sole
     * paginator the connector needs — a request-specific override via
     * `HasRequestPagination` is not something any request here declares.
     */
    public function paginate(Request $request): Paginator
    {
        return new Cin7Paginator($this, $request);
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
     * Throttle every Cin7 call against one shared window.
     *
     * The key is deliberately connector-wide rather than per-request: Cin7
     * meters the account, not the endpoint. It is also keyed by account, so
     * two connectors for different Cin7 accounts — multi-tenant, or sandbox
     * alongside production — do not throttle each other on a shared store.
     * A non-positive max or period disables throttling entirely.
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
     * Resolve the selected rate limiter store.
     *
     * Null defers to `saloon.rate_limiter.store` and then to the limiter
     * manager's own default store, which an application ships as the shared
     * `database` store. A per-worker store (`worker-array`, Testbench's
     * default) is not shared across workers or servers, so selecting one
     * multiplies the effective account limit by the worker/node count.
     */
    protected function resolveRateLimitStore(): UnitEnum|string|null
    {
        return $this->rateLimitStore;
    }

    /**
     * Wait for capacity rather than throwing once the window is exhausted.
     *
     * The wait runs through `Hypervel\Support\Sleep`, whose native sleep is
     * Swoole-hooked, so it suspends only the calling coroutine.
     */
    protected function waitForRateLimits(): bool
    {
        return true;
    }

    /**
     * Resolve the stable cooldown key for an operation.
     *
     * The `HasRateLimits` default keys the cooldown on `static::class` alone,
     * which — like the unkeyed limiter policy above — would let a 503 from one
     * Cin7 account's connector impose its cooldown on every other account
     * sharing the same store.
     */
    protected function resolveRateLimitCooldownKey(PendingRequest $pendingRequest): string
    {
        return self::class . ':' . $this->accountId;
    }

    /**
     * Map a throttling response onto a limiter cooldown.
     *
     * Cin7 signals throttling with a 503 and no `Retry-After`, which the
     * trait's default parser (429 + Retry-After only) cannot read — so this
     * deliberately does not fall through to it.
     *
     * Note this hook is defined by the `HasRateLimits` trait, not by
     * `Connector`: the override below shadows the trait method, and
     * `parent::resolveRateLimitCooldown()` would be fatal. If the trait
     * default is ever wanted alongside this, alias it in instead:
     * `use HasRateLimits { resolveRateLimitCooldown as baseResolveRateLimitCooldown; }`.
     *
     * The manager calls this for every response that came off the wire —
     * including 200s — and never for mocked or cached ones, so faked tests
     * cannot exercise it. `RateLimitTest` covers it directly.
     */
    protected function resolveRateLimitCooldown(Response $response): ?int
    {
        return $response->status() === 503 ? 5 : null;
    }
}
