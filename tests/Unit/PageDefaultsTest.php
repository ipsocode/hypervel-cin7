<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use InvalidArgumentException;
use Ipsocode\Cin7\PageDefaults;
use PHPUnit\Framework\Attributes\DataProvider;

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
        $this->assertSame(1000, PageDefaults::LIMIT_MAX);
    }

    /**
     * Cin7 serves pages of 1 to 1000 records; a numeric string is accepted as it is sent.
     */
    #[UnitTest]
    public function testTheBoundsAreAccepted(): void
    {
        $this->assertSame(
            ['page' => 1, 'limit' => 1000],
            PageDefaults::apply(['page' => 1, 'limit' => 1000]),
        );
        $this->assertSame(
            ['page' => '2', 'limit' => '1'],
            PageDefaults::apply(['page' => '2', 'limit' => '1']),
        );
    }

    /**
     * @param array<string, mixed> $parameters
     */
    #[UnitTest]
    #[DataProvider('outOfBoundsProvider')]
    public function testAPageOrLimitOutOfBoundsThrows(array $parameters, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        PageDefaults::apply($parameters);
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function outOfBoundsProvider(): array
    {
        return [
            'page 0' => [['page' => 0], 'The Cin7 page must be a whole number of at least 1, got 0.'],
            'a page that is not a number' => [['page' => 'first'], "The Cin7 page must be a whole number of at least 1, got 'first'."],
            'limit 0' => [['limit' => 0], 'The Cin7 limit must be a whole number from 1 to 1000, got 0.'],
            'limit 1001' => [['limit' => 1001], 'The Cin7 limit must be a whole number from 1 to 1000, got 1001.'],
            'a fractional limit' => [['limit' => 2.5], 'The Cin7 limit must be a whole number from 1 to 1000, got 2.5.'],
            'an array limit' => [['limit' => [10]], 'The Cin7 limit must be a whole number from 1 to 1000, got array.'],
        ];
    }

    #[UnitTest]
    public function testItDoesNotMutateTheCallersArray(): void
    {
        $parameters = ['Name' => 'ACME'];

        PageDefaults::apply($parameters);

        $this->assertSame(['Name' => 'ACME'], $parameters);
    }
}
