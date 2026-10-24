<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit;

use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Ipsocode\Cin7\Tests\Catalogue;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The shards the big catalogue test classes run split each kind's rows with none lost or repeated.
 *
 * @see docs/testing.md
 */
class CatalogueShardTest extends TestCase
{
    #[UnitTest]
    #[DataProvider('kindProvider')]
    public function testTheShardsTogetherHoldEveryRowOnce(string $kind): void
    {
        $keys = [];

        for ($shard = 0; $shard < Catalogue::SHARDS; ++$shard) {
            $rows = Catalogue::shard($kind, $shard);

            $this->assertNotEmpty($rows, "Shard {$shard} of '{$kind}' holds no row.");

            array_push($keys, ...array_keys($rows));
        }

        $all = array_keys(Catalogue::rows($kind));

        $this->assertCount(count($all), $keys);
        $this->assertEqualsCanonicalizing($all, $keys);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function kindProvider(): array
    {
        return [
            'dtos' => ['dtos'],
            'bodies' => ['bodies'],
            'missing' => ['missing'],
            'requests' => ['requests'],
            'resources' => ['resources'],
        ];
    }
}
