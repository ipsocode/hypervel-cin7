<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Ipsocode\Cin7\Resources\CustomerResource;
use Ipsocode\Cin7\Resources\ProductResource;
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
}
