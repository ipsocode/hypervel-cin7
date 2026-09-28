<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use PHPUnit\Framework\Attributes\DataProvider;
use ValueError;

/**
 * The endpoint table is data, so it is tested as data: one provider row per
 * case, transcribed from the vendor `Api/*` classes it replaces.
 */
class EndpointTest extends TestCase
{
    /**
     * @param list<Method> $writeMethods
     */
    #[UnitTest]
    #[DataProvider('endpointProvider')]
    public function testTheEndpointTable(
        Endpoint $endpoint,
        string $path,
        string $guidKey,
        string $deleteGuidKey,
        array $writeMethods,
    ): void {
        $this->assertSame($path, $endpoint->path());
        $this->assertSame($guidKey, $endpoint->guidKey());
        $this->assertSame($deleteGuidKey, $endpoint->deleteGuidKey());
        $this->assertSame($writeMethods, $endpoint->writeMethods());
    }

    /**
     * The §3.3 table, transcribed from the vendor `Api/*` classes.
     *
     * @return array<string, array{Endpoint, string, string, string, list<Method>}>
     */
    public static function endpointProvider(): array
    {
        return [
            'customer' => [Endpoint::Customer, 'customer', 'ID', 'ID', [Method::POST, Method::PUT]],
            'product' => [Endpoint::Product, 'product', 'ID', 'ID', [Method::POST, Method::PUT]],
            'sale' => [Endpoint::Sale, 'sale', 'ID', 'ID', [Method::POST, Method::PUT, Method::DELETE]],
            'saleInvoice' => [Endpoint::SaleInvoice, 'sale/invoice', 'SaleID', 'ID', [Method::POST, Method::DELETE]],
            'saleOrder' => [Endpoint::SaleOrder, 'sale/order', 'SaleID', 'ID', [Method::POST]],
            'saleCreditNote' => [Endpoint::SaleCreditNote, 'sale/creditnote', 'SaleID', 'ID', [Method::POST, Method::DELETE]],
            'salePayment' => [Endpoint::SalePayment, 'sale/payment', 'ID', 'ID', [Method::POST, Method::PUT, Method::DELETE]],
            'saleList' => [Endpoint::SaleList, 'saleList', 'SaleID', 'ID', []],
            'tax' => [Endpoint::Tax, 'ref/tax', 'ID', 'ID', [Method::POST, Method::PUT]],
            'moneyOperation' => [Endpoint::MoneyOperation, 'moneyOperation', 'ID', 'ID', [Method::POST, Method::PUT, Method::DELETE]],
            'customerCredits' => [Endpoint::CustomerCredits, 'ref/customer/credits', 'CustomerID', 'ID', []],
        ];
    }

    /**
     * Adding a case without adding its row would otherwise leave that endpoint's
     * path and keys entirely unasserted — and `path()` would fatal on it, since
     * the match arms are exhaustive.
     */
    #[UnitTest]
    public function testEveryCaseIsCoveredByTheTable(): void
    {
        $this->assertCount(count(Endpoint::cases()), self::endpointProvider());
    }

    #[UnitTest]
    public function testFromAccessorResolvesTheAccessorUsedByCallers(): void
    {
        $this->assertSame(Endpoint::SaleInvoice, Endpoint::fromAccessor('saleInvoice'));
        $this->assertSame(Endpoint::Tax, Endpoint::fromAccessor('tax'));
    }

    /**
     * The case *values* are the accessor names the package this replaced
     * exposed through `__call()`, so existing callers can pass their endpoint
     * strings straight through. A case whose value drifted from its accessor
     * would break them silently.
     */
    #[UnitTest]
    public function testEveryCaseValueIsResolvableAsAnAccessor(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame($endpoint, Endpoint::fromAccessor($endpoint->value));
        }
    }

    #[UnitTest]
    public function testFromAccessorRejectsAnUnknownAccessor(): void
    {
        $this->expectException(ValueError::class);

        Endpoint::fromAccessor('nope');
    }

    /**
     * The accessor is case-sensitive: `Customer` is a case *name*, not a case
     * value, and enum `from()` matches on the value.
     */
    #[UnitTest]
    public function testFromAccessorIsCaseSensitive(): void
    {
        $this->expectException(ValueError::class);

        Endpoint::fromAccessor('Customer');
    }

    /**
     * The legacy client's domain models inherit an empty `$path`, and the old
     * package answered that with a `BadMethodCallException` its caller
     * swallowed. A `ValueError` here keeps that contract intact.
     */
    #[UnitTest]
    public function testFromAccessorRejectsAnEmptyAccessor(): void
    {
        $this->expectException(ValueError::class);

        Endpoint::fromAccessor('');
    }

    /**
     * GET is granted by Cin7Request rather than listed per endpoint, so a case
     * that accidentally listed it would widen the verb gate by one method
     * without any test noticing.
     */
    #[UnitTest]
    public function testNoEndpointListsGetAmongItsWriteMethods(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertNotContains(Method::GET, $endpoint->writeMethods(), $endpoint->value);
        }
    }

    /**
     * Read-only endpoints are the reason the gate exists; naming them here means
     * turning one writable is a deliberate edit rather than a side effect.
     */
    #[UnitTest]
    public function testTheReadOnlyEndpointsAreSaleListAndCustomerCredits(): void
    {
        $readOnly = array_values(array_filter(
            Endpoint::cases(),
            fn (Endpoint $endpoint): bool => $endpoint->writeMethods() === [],
        ));

        $this->assertSame([Endpoint::SaleList, Endpoint::CustomerCredits], $readOnly);
    }

    /**
     * Every delete goes out under `ID`, including the `sale/*` endpoints that
     * find under `SaleID`. That asymmetry is upstream's observed behavior, not
     * an oversight, so it is asserted rather than left to the per-case rows.
     */
    #[UnitTest]
    public function testEveryEndpointDeletesByIdRegardlessOfItsFindKey(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame('ID', $endpoint->deleteGuidKey(), $endpoint->value);
        }
    }
}
