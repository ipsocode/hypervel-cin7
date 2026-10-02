<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

/**
 * The `page`/`limit` defaults for list query strings, never keyed reads, deletes or write bodies.
 *
 * The defaults are lowercase, and a caller's `Page`/`Limit` in any case suppresses them, so
 * a request never carries both spellings.
 *
 * @see docs/requests.md
 */
final class PageDefaults
{
    public const int PAGE = 1;

    public const int LIMIT = 100;

    /**
     * Add the page and limit defaults, leaving non-null caller values alone.
     *
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    public static function apply(array $parameters): array
    {
        foreach (['page' => self::PAGE, 'limit' => self::LIMIT] as $key => $default) {
            if (! self::isSet($parameters, $key)) {
                $parameters[$key] = $default;
            }
        }

        return $parameters;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private static function isSet(array $parameters, string $key): bool
    {
        foreach ($parameters as $name => $value) {
            if ($value !== null && strtolower((string) $name) === $key) {
                return true;
            }
        }

        return false;
    }
}
