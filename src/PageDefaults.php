<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

/**
 * The `page`/`limit` defaults for list query strings, never keyed reads, deletes or write bodies.
 *
 * The keys are lowercase on purpose, so a caller's `Page`/`Limit` does not suppress them.
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
        $parameters['page'] ??= self::PAGE;
        $parameters['limit'] ??= self::LIMIT;

        return $parameters;
    }
}
