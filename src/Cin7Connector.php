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
use Ipsocode\Cin7\Resources\AdvancedPurchaseResource;
use Ipsocode\Cin7\Resources\AdvancedSaleResource;
use Ipsocode\Cin7\Resources\BankTransferResource;
use Ipsocode\Cin7\Resources\CustomerResource;
use Ipsocode\Cin7\Resources\CustomPricesResource;
use Ipsocode\Cin7\Resources\InventoryWriteOffListResource;
use Ipsocode\Cin7\Resources\InventoryWriteOffResource;
use Ipsocode\Cin7\Resources\JournalResource;
use Ipsocode\Cin7\Resources\MeResource;
use Ipsocode\Cin7\Resources\MoneyTaskListResource;
use Ipsocode\Cin7\Resources\MoneyTaskResource;
use Ipsocode\Cin7\Resources\ProductFamilyResource;
use Ipsocode\Cin7\Resources\ProductResource;
use Ipsocode\Cin7\Resources\ProductSuppliersResource;
use Ipsocode\Cin7\Resources\PurchaseCreditNoteListResource;
use Ipsocode\Cin7\Resources\PurchaseListResource;
use Ipsocode\Cin7\Resources\PurchaseResource;
use Ipsocode\Cin7\Resources\ReferenceResource;
use Ipsocode\Cin7\Resources\RefResource;
use Ipsocode\Cin7\Resources\SaleCreditNoteListResource;
use Ipsocode\Cin7\Resources\SaleListResource;
use Ipsocode\Cin7\Resources\SaleResource;
use Ipsocode\Cin7\Resources\StockAdjustmentListResource;
use Ipsocode\Cin7\Resources\StockAdjustmentResource;
use Ipsocode\Cin7\Resources\StockTakeListResource;
use Ipsocode\Cin7\Resources\StockTakeResource;
use Ipsocode\Cin7\Resources\StockTransferListResource;
use Ipsocode\Cin7\Resources\StockTransferResource;
use Ipsocode\Cin7\Resources\SupplierResource;
use Ipsocode\Cin7\Resources\TransactionsResource;
use Ipsocode\Cin7\Resources\WebhooksResource;
use UnitEnum;

/**
 * The Cin7 Core connector.
 *
 * It holds only readonly values, all set in the constructor, so one singleton per worker is
 * shared across coroutines.
 *
 * @see docs/connector.md
 */
final class Cin7Connector extends Connector implements HasPagination
{
    use HasRateLimits {
        resolveRateLimitCooldown as retryAfterCooldown;
    }

    /**
     * The cooldown, in seconds, after a throttling response that names no `Retry-After`.
     */
    public const int THROTTLE_COOLDOWN = 5;

    /**
     * @var array<string, string>
     */
    private readonly array $headers;

    /**
     * Cin7 meters each API application, so the window and the cooldown are keyed by the account
     * and a short digest of the application key, never the key itself.
     */
    private readonly string $rateLimitKey;

    /**
     * @var list<AdmissionPolicy>
     */
    private readonly array $rateLimitPolicies;

    public function __construct(
        private readonly string $accountId,
        private readonly string $applicationKey,
        private readonly int $rateLimitMax = 60,
        private readonly int $rateLimitPeriod = 60,
        private readonly ?string $rateLimitStore = null,
    ) {
        $this->headers = [
            'Content-Type' => 'application/json',
            'api-auth-accountid' => $this->accountId,
            'api-auth-applicationkey' => $this->applicationKey,
        ];
        $this->rateLimitKey = 'cin7:api:' . $this->accountId . ':' . substr(hash('sha256', $this->applicationKey), 0, 16);
        // A non-positive max or period disables the window.
        $this->rateLimitPolicies = $this->rateLimitMax > 0 && $this->rateLimitPeriod > 0
            ? [new Limit($this->rateLimitMax, $this->rateLimitPeriod)->by($this->rateLimitKey)]
            : [];
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
     * Fail a response whose body is Cin7's Error Model, `{ErrorCode, Exception}` or a list
     * starting with one, whatever its status; anything else is left to the status code.
     */
    public function hasRequestFailed(Response $response): ?bool
    {
        $body = $response->json();

        if (! is_array($body)) {
            return null;
        }

        $error = array_is_list($body) ? ($body[0] ?? null) : $body;

        return is_array($error) && array_key_exists('ErrorCode', $error) ? true : null;
    }

    /**
     * The `advanced-purchase` resource, the advanced purchase.
     */
    public function advancedPurchase(): AdvancedPurchaseResource
    {
        return new AdvancedPurchaseResource($this);
    }

    /**
     * The advanced sale, served by the `sale` endpoints.
     */
    public function advancedSale(): AdvancedSaleResource
    {
        return new AdvancedSaleResource($this);
    }

    /**
     * The `customer` resource.
     */
    public function customer(): CustomerResource
    {
        return new CustomerResource($this);
    }

    /**
     * The `me` resource, the company the API application belongs to.
     */
    public function me(): MeResource
    {
        return new MeResource($this);
    }

    /**
     * The Money Task resource, on `moneyOperation`.
     */
    public function moneyTask(): MoneyTaskResource
    {
        return new MoneyTaskResource($this);
    }

    /**
     * The `moneyTaskList` resource.
     */
    public function moneyTaskList(): MoneyTaskListResource
    {
        return new MoneyTaskListResource($this);
    }

    /**
     * The `product` resource.
     */
    public function product(): ProductResource
    {
        return new ProductResource($this);
    }

    /**
     * The `purchase` resource, the simple purchase.
     */
    public function purchase(): PurchaseResource
    {
        return new PurchaseResource($this);
    }

    /**
     * The `purchaseCreditNoteList` resource.
     */
    public function purchaseCreditNoteList(): PurchaseCreditNoteListResource
    {
        return new PurchaseCreditNoteListResource($this);
    }

    /**
     * The `purchaseList` resource.
     */
    public function purchaseList(): PurchaseListResource
    {
        return new PurchaseListResource($this);
    }

    /**
     * The `productFamily` resource.
     */
    public function productFamily(): ProductFamilyResource
    {
        return new ProductFamilyResource($this);
    }

    /**
     * The `ref` grouping: `ref()->tax()`, `ref()->account()`, `ref()->paymentTerm()` and the
     * other `ref/…` resources.
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
     * The `saleCreditNoteList` resource.
     */
    public function saleCreditNoteList(): SaleCreditNoteListResource
    {
        return new SaleCreditNoteListResource($this);
    }

    /**
     * The `saleList` resource.
     */
    public function saleList(): SaleListResource
    {
        return new SaleListResource($this);
    }

    /**
     * The `bankTransfer` resource.
     */
    public function bankTransfer(): BankTransferResource
    {
        return new BankTransferResource($this);
    }

    /**
     * The `journal` resource.
     */
    public function journal(): JournalResource
    {
        return new JournalResource($this);
    }

    /**
     * The `transactions` resource.
     */
    public function transactions(): TransactionsResource
    {
        return new TransactionsResource($this);
    }

    /**
     * The `supplier` resource.
     */
    public function supplier(): SupplierResource
    {
        return new SupplierResource($this);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Throttle every call against one window per Cin7 API application.
     *
     * @return list<AdmissionPolicy>
     */
    protected function resolveRateLimits(PendingRequest $pendingRequest): array
    {
        return $this->rateLimitPolicies;
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
     * Keyed like the window, so throttling on one Cin7 application never cools down another.
     */
    protected function resolveRateLimitCooldownKey(PendingRequest $pendingRequest): string
    {
        return $this->rateLimitKey;
    }

    /**
     * Cin7 throttles with a 429 (its documented limit response) or a 503. A 429 cools down for its
     * `Retry-After`, parsed by the `HasRateLimits` method aliased as `retryAfterCooldown()`; a 429
     * without one, and every 503, for `THROTTLE_COOLDOWN` seconds.
     */
    protected function resolveRateLimitCooldown(Response $response): ?int
    {
        return match ($response->status()) {
            429 => $this->retryAfterCooldown($response) ?? self::THROTTLE_COOLDOWN,
            503 => self::THROTTLE_COOLDOWN,
            default => null,
        };
    }

    /**
     * The `custom-prices` resource.
     */
    public function customPrices(): CustomPricesResource
    {
        return new CustomPricesResource($this);
    }

    /**
     * The `product-suppliers` resource.
     */
    public function productSuppliers(): ProductSuppliersResource
    {
        return new ProductSuppliersResource($this);
    }

    /**
     * The `reference/…` resources, the reference books.
     */
    public function reference(): ReferenceResource
    {
        return new ReferenceResource($this);
    }

    /**
     * The `stockadjustmentList` resource.
     */
    public function stockAdjustmentList(): StockAdjustmentListResource
    {
        return new StockAdjustmentListResource($this);
    }

    /**
     * The `stockadjustment` resource.
     */
    public function stockAdjustment(): StockAdjustmentResource
    {
        return new StockAdjustmentResource($this);
    }

    /**
     * The `stockTakeList` resource.
     */
    public function stockTakeList(): StockTakeListResource
    {
        return new StockTakeListResource($this);
    }

    /**
     * The `stocktake` resource.
     */
    public function stockTake(): StockTakeResource
    {
        return new StockTakeResource($this);
    }

    /**
     * The `stockTransferList` resource.
     */
    public function stockTransferList(): StockTransferListResource
    {
        return new StockTransferListResource($this);
    }

    /**
     * The `stockTransfer` resource.
     */
    public function stockTransfer(): StockTransferResource
    {
        return new StockTransferResource($this);
    }

    /**
     * The `inventoryWriteOffList` resource.
     */
    public function inventoryWriteOffList(): InventoryWriteOffListResource
    {
        return new InventoryWriteOffListResource($this);
    }

    /**
     * The `inventoryWriteOff` resource.
     */
    public function inventoryWriteOff(): InventoryWriteOffResource
    {
        return new InventoryWriteOffResource($this);
    }

    /**
     * The `webhooks` resource.
     */
    public function webhooks(): WebhooksResource
    {
        return new WebhooksResource($this);
    }
}
