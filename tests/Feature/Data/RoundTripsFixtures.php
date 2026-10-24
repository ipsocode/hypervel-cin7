<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

/**
 * The round-trip assertion the data catalog tests share.
 */
trait RoundTripsFixtures
{
    /**
     * Assert a model's `toArray()` holds the fixture: every fixture key is modeled under its
     * wire name with an equal value, and every other key is null, the default of a field the
     * fixture left out. Absent and `null` are the same to a model.
     *
     * @param array<array-key, mixed> $expected
     * @param array<array-key, mixed> $actual
     */
    protected function assertRoundTrips(array $expected, array $actual, string $path = ''): void
    {
        foreach ($expected as $key => $value) {
            $at = $path === '' ? (string) $key : $path . '.' . $key;

            $this->assertArrayHasKey($key, $actual, $at);

            if (is_array($value) && is_array($actual[$key])) {
                $this->assertRoundTrips($value, $actual[$key], $at);
            } else {
                $this->assertEquals($value, $actual[$key], $at);
            }
        }

        foreach (array_diff_key($actual, $expected) as $key => $value) {
            $this->assertNull($value, $path === '' ? (string) $key : $path . '.' . $key);
        }
    }
}
