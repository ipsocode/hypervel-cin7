<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Ipsocode\Cin7\PageDefaults;

/**
 * The `Helper::prepareParameters()` port. Four lines of code, but the four
 * behaviors below are the ones upstream callers depend on by accident, so each
 * gets its own case.
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

    /**
     * Upstream used `isset()`, so a null value was treated as absent.
     */
    #[UnitTest]
    public function testNullValuesAreTreatedAsAbsent(): void
    {
        $this->assertSame(
            ['page' => 1, 'limit' => 100],
            PageDefaults::apply(['page' => null, 'limit' => null]),
        );
    }

    /**
     * Cin7 accepts `Page`/`Limit` as well, and callers that paginate themselves
     * send both casings today — so the capitalised spelling must not satisfy the
     * `??=` and suppress the lowercase defaults the package guarantees.
     */
    #[UnitTest]
    public function testTheCapitalisedSpellingDoesNotSuppressTheDefaults(): void
    {
        $this->assertSame(
            ['Page' => 5, 'Limit' => 20, 'page' => 1, 'limit' => 100],
            PageDefaults::apply(['Page' => 5, 'Limit' => 20]),
        );
    }

    /**
     * The defaults are appended, never prepended: a caller's own keys keep
     * their position, which is what makes an assertSame() on the whole query
     * array stable across the request tests.
     */
    #[UnitTest]
    public function testTheDefaultsAreAppendedAfterCallerKeys(): void
    {
        $this->assertSame(
            ['Name', 'Status', 'page', 'limit'],
            array_keys(PageDefaults::apply(['Name' => 'ACME', 'Status' => 'Active'])),
        );
    }

    /**
     * The constants are public API — the request classes do not restate them,
     * and a consumer paginating by hand reads them rather than hard-coding 100.
     */
    #[UnitTest]
    public function testTheDefaultsAreExposedAsConstants(): void
    {
        $this->assertSame(1, PageDefaults::PAGE);
        $this->assertSame(100, PageDefaults::LIMIT);
    }

    /**
     * `apply()` takes the array by value; a caller holding the original must
     * not see it change underneath them.
     */
    #[UnitTest]
    public function testItDoesNotMutateTheCallersArray(): void
    {
        $parameters = ['Name' => 'ACME'];

        PageDefaults::apply($parameters);

        $this->assertSame(['Name' => 'ACME'], $parameters);
    }
}
