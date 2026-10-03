<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Data\AdvancedSale\AdvancedSalePostData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Enums\SaleType;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\PostSaleFulfilment;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * The advanced sale has no endpoints of its own: `advancedSale()` sends the `sale` requests.
 *
 * @see docs/resources.md
 */
class AdvancedSaleTest extends TestCase
{
    private const string SALE_ID = '916ab4c0-6ccb-4c93-873d-0603859050e4';

    public function testTheBodyAlwaysSendsAnAdvancedSaleType(): void
    {
        $this->assertSame('Advanced', AdvancedSalePostData::from(['Location' => 'Main Warehouse', 'CurrencyRate' => 1])->toArray()['SaleType']);
    }

    /**
     * Adding a fulfilment to a simple sale makes it an advanced sale: the sale reads `Simple Sale`
     * before and `Advanced Sale` after, through the same `sale` request, and every document of an
     * advanced sale is a list.
     */
    public function testASecondFulfilmentTurnsASimpleSaleAdvanced(): void
    {
        $simple = [...Cin7Payloads::sale(self::SALE_ID), 'Type' => 'Simple Sale'];
        $advanced = [...Cin7Payloads::sale(self::SALE_ID), 'Type' => 'Advanced Sale'];

        $mock = Saloon::fake([
            MockResponse::make($simple),
            MockResponse::make(Cin7Payloads::load('sale/fulfilment', 'get.response')),
            MockResponse::make($advanced),
        ]);

        $before = $this->connector()->advancedSale()->get(self::SALE_ID)->dto();
        $this->assertInstanceOf(SaleData::class, $before);
        $this->assertSame(SaleType::SimpleSale, $before->Type);

        $this->connector()->advancedSale()->fulfilment()->post(['SaleID' => self::SALE_ID]);

        $after = $this->connector()->advancedSale()->get(self::SALE_ID)->dto();
        $this->assertInstanceOf(SaleData::class, $after);
        $this->assertSame(SaleType::AdvancedSale, $after->Type);
        $this->assertSame(self::SALE_ID, $after->ID);

        $mock->assertSent(PostSaleFulfilment::class);
        $mock->assertSentCount(3);
    }

    public function testThePostGoesToTheSaleEndpoint(): void
    {
        $mock = Saloon::fake([PostSale::class => MockResponse::make(Cin7Payloads::sale(self::SALE_ID))]);

        $this->connector()->advancedSale()->post(AdvancedSalePostData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1]));

        $mock->assertSent(PostSale::class);
    }
}
