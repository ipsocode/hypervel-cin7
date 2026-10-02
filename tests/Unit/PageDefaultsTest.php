<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Ipsocode\Cin7\PageDefaults;

/**
 * One case per behavior callers rely on.
 *
 * @see docs/requests.md
 */
class PageDefaultsTest extends TestCase
{
    #[UnitTest]
    public function testItInjectsTheDefaultsWhenAbsent(): void
    {
        $this->assertSame(
            ['page' => 1, 'limit' => 100],
            PageDefaults::apply([]),
        );
    }

    #[UnitTest]
    public function testCallerValuesWin(): void
    {
        $this->assertSame(
            ['page' => 3, 'limit' => 25],
            PageDefaults::apply(['page' => 3, 'limit' => 25]),
        );
    }

    #[UnitTest]
    public function testItFillsOnlyTheMissingSideAndKeepsOtherParameters(): void
    {
        $this->assertSame(
            ['Name' => 'ACME', 'page' => 2, 'limit' => 100],
            PageDefaults::apply(['Name' => 'ACME', 'page' => 2]),
        );
    }

    #[UnitTest]
    public function testNullValuesAreTreatedAsAbsent(): void
    {
        $this->assertSame(
            ['page' => 1, 'limit' => 100],
            PageDefaults::apply(['page' => null, 'limit' => null]),
        );
    }

    /**
     * Cin7 accepts `Page`/`Limit` too, so a request never carries both spellings.
     */
    #[UnitTest]
    public function testTheCapitalisedSpellingSuppressesTheDefaults(): void
    {
        $this->assertSame(
            ['Page' => 5, 'Limit' => 20],
            PageDefaults::apply(['Page' => 5, 'Limit' => 20]),
        );
    }

    #[UnitTest]
    public function testANullCapitalisedValueDoesNotSuppressTheDefault(): void
    {
        $this->assertSame(
            ['Page' => null, 'page' => 1, 'limit' => 100],
            PageDefaults::apply(['Page' => null]),
        );
    }

    /**
     * The request tests assertSame() whole query arrays, so key order is part of the contract.
     */
    #[UnitTest]
    public function testTheDefaultsAreAppendedAfterCallerKeys(): void
    {
        $this->assertSame(
            ['Name', 'Status', 'page', 'limit'],
            array_keys(PageDefaults::apply(['Name' => 'ACME', 'Status' => 'Active'])),
        );
    }

    #[UnitTest]
    public function testTheDefaultsAreExposedAsConstants(): void
    {
        $this->assertSame(1, PageDefaults::PAGE);
        $this->assertSame(100, PageDefaults::LIMIT);
    }

    #[UnitTest]
    public function testItDoesNotMutateTheCallersArray(): void
    {
        $parameters = ['Name' => 'ACME'];

        PageDefaults::apply($parameters);

        $this->assertSame(['Name' => 'ACME'], $parameters);
    }
}
