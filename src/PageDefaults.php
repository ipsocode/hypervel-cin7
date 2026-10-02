<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

/**
 * The `page`/`limit` defaults for list query strings, never keyed reads, deletes or write bodies.
 *
 * The keys are lowercase; a caller's `Page`/`Limit` in any case is sent under them.
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
     * A caller's `Page`/`Limit` in any case is sent as lowercase `page`/`limit`, so a request
     * never carries both spellings and the paginator reads the limit it sent.
     *
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    public static function apply(array $parameters): array
    {
        $applied = [];

        foreach ($parameters as $name => $value) {
            $lower = strtolower((string) $name);

            if ($lower !== 'page' && $lower !== 'limit') {
                $applied[$name] = $value;
            } elseif ($value !== null && ($name === $lower || ! isset($applied[$lower]))) {
                $applied[$lower] = $value;
            }
        }

        $applied['page'] ??= self::PAGE;
        $applied['limit'] ??= self::LIMIT;

        return $applied;
    }
}
