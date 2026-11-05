<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Support;

use InvalidArgumentException;

/**
 * Reads a numeric config value and rejects one outside its range, rather than running with a value
 * nobody chose.
 *
 * @see src/docs/configuration.md
 */
class ConfigRange
{
    /**
     * Return the integer at the key, from `$min` to `$max`.
     *
     * @throws InvalidArgumentException when the value is outside the range
     */
    public static function integer(string $key, int $min, int $max = PHP_INT_MAX): int
    {
        $value = config()->integer($key);

        if ($value < $min || $value > $max) {
            throw self::outOfRange($key, $value, self::describe($min, $max));
        }

        return $value;
    }

    /**
     * Return the float at the key, at least `$min`.
     *
     * @throws InvalidArgumentException when the value is below the minimum
     */
    public static function float(string $key, float $min): float
    {
        $value = config()->float($key);

        if ($value < $min) {
            throw self::outOfRange($key, $value, 'at least ' . $min);
        }

        return $value;
    }

    private static function describe(int $min, int $max): string
    {
        return $max === PHP_INT_MAX ? 'at least ' . $min : sprintf('%d to %d', $min, $max);
    }

    private static function outOfRange(string $key, float|int $value, string $range): InvalidArgumentException
    {
        return new InvalidArgumentException(sprintf(
            'Configuration value for key [%s] must be %s, %s given.',
            $key,
            $range,
            $value,
        ));
    }
}
