<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use PHPUnit\Framework\Attributes\DataProvider;
use ValueError;

/**
 * The endpoint table is data, so it is tested as data: one provider row per case.
 *
 * @see docs/endpoints.md
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
     * `Customer` is a case name, not a case value, and `fromAccessor()` matches on the value.
     */
    #[UnitTest]
    public function testFromAccessorIsCaseSensitive(): void
    {
        $this->expectException(ValueError::class);

        Endpoint::fromAccessor('Customer');
    }

    #[UnitTest]
    public function testFromAccessorRejectsAnEmptyAccessor(): void
    {
        $this->expectException(ValueError::class);

        Endpoint::fromAccessor('');
    }

    /**
     * GET is granted by Cin7Request, so a case listing it would widen the verb gate unnoticed.
     */
    #[UnitTest]
    public function testNoEndpointListsGetAmongItsWriteMethods(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertNotContains(Method::GET, $endpoint->writeMethods(), $endpoint->value);
        }
    }

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
     * Every endpoint modelled here deletes under `ID`, including the `sale/*` ones that
     * find by `SaleID`. A case with another delete key updates this test.
     */
    #[UnitTest]
    public function testEveryEndpointDeletesByIdRegardlessOfItsFindKey(): void
    {
        foreach (Endpoint::cases() as $endpoint) {
            $this->assertSame('ID', $endpoint->deleteGuidKey(), $endpoint->value);
        }
    }
}
