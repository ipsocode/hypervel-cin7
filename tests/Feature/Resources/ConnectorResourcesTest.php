<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Ipsocode\Cin7\Resources\CustomerResource;
use Ipsocode\Cin7\Resources\ProductResource;
use Ipsocode\Cin7\Resources\Ref\Customer\CreditsResource;
use Ipsocode\Cin7\Resources\Ref\CustomerResource as RefCustomerResource;
use Ipsocode\Cin7\Resources\Ref\TaxResource;
use Ipsocode\Cin7\Resources\RefResource;
use Ipsocode\Cin7\Resources\Sale\CreditNoteResource;
use Ipsocode\Cin7\Resources\Sale\InvoiceResource;
use Ipsocode\Cin7\Resources\Sale\OrderResource;
use Ipsocode\Cin7\Resources\Sale\PaymentResource;
use Ipsocode\Cin7\Resources\SaleListResource;
use Ipsocode\Cin7\Resources\SaleResource;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * Every accessor on the connector returns its resource class, fresh on each call so the
 * connector singleton stays coroutine-safe.
 *
 * @see docs/resources.md
 */
class ConnectorResourcesTest extends TestCase
{
    public function testCustomerReturnsACustomerResource(): void
    {
        $this->assertInstanceOf(CustomerResource::class, $this->connector()->customer());
    }

    public function testCustomerReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->customer(), $connector->customer());
    }

    public function testProductReturnsAProductResource(): void
    {
        $this->assertInstanceOf(ProductResource::class, $this->connector()->product());
    }

    public function testProductReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->product(), $connector->product());
    }

    public function testSaleAndSaleListReturnTheirResources(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(SaleResource::class, $connector->sale());
        $this->assertInstanceOf(SaleListResource::class, $connector->saleList());
        $this->assertNotSame($connector->sale(), $connector->sale());
        $this->assertNotSame($connector->saleList(), $connector->saleList());
    }

    public function testSaleReturnsItsNestedResources(): void
    {
        $sale = $this->connector()->sale();

        $this->assertInstanceOf(OrderResource::class, $sale->order());
        $this->assertInstanceOf(InvoiceResource::class, $sale->invoice());
        $this->assertInstanceOf(CreditNoteResource::class, $sale->creditNote());
        $this->assertInstanceOf(PaymentResource::class, $sale->payment());
        $this->assertNotSame($sale->order(), $sale->order());
    }

    public function testRefReturnsARefResourceWithItsGroupings(): void
    {
        $ref = $this->connector()->ref();

        $this->assertInstanceOf(RefResource::class, $ref);
        $this->assertInstanceOf(TaxResource::class, $ref->tax());
        $this->assertInstanceOf(RefCustomerResource::class, $ref->customer());
        $this->assertInstanceOf(CreditsResource::class, $ref->customer()->credits());
    }

    public function testRefReturnsAFreshInstanceEveryCall(): void
    {
        $connector = $this->connector();

        $this->assertNotSame($connector->ref(), $connector->ref());
        $this->assertNotSame($connector->ref()->customer(), $connector->ref()->customer());
    }
}
