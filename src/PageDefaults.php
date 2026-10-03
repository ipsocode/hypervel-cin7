<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use InvalidArgumentException;

/**
 * The `page`/`limit` defaults and bounds for list query strings, never keyed reads, deletes or
 * write bodies.
 *
 * @see docs/requests.md
 */
final class PageDefaults
{
    public const int PAGE = 1;

    public const int LIMIT = 100;

    /**
     * The largest page size Cin7 serves.
     */
    public const int LIMIT_MAX = 1000;

    /**
     * Add the page and limit defaults after the other parameters, leaving non-null caller values
     * alone.
     *
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException when the page is below 1 or the limit outside 1 to LIMIT_MAX
     */
    public static function apply(array $parameters): array
    {
        $parameters['page'] ??= self::PAGE;
        $parameters['limit'] ??= self::LIMIT;

        self::ensureValidPage($parameters['page']);
        self::ensureValidLimit($parameters['limit']);

        return $parameters;
    }

    /**
     * Throw unless the page is a whole number of at least 1.
     *
     * @throws InvalidArgumentException
     */
    public static function ensureValidPage(mixed $page): void
    {
        $number = filter_var($page, FILTER_VALIDATE_INT);

        if ($number === false || $number < 1) {
            throw new InvalidArgumentException(sprintf(
                'The Cin7 page must be a whole number of at least 1, got %s.',
                self::describe($page),
            ));
        }
    }

    /**
     * Throw unless the limit is a whole number from 1 to LIMIT_MAX: Cin7 serves no larger page, and
     * the paginator counts pages by the limit it sent.
     *
     * @throws InvalidArgumentException
     */
    public static function ensureValidLimit(mixed $limit): void
    {
        $number = filter_var($limit, FILTER_VALIDATE_INT);

        if ($number === false || $number < 1 || $number > self::LIMIT_MAX) {
            throw new InvalidArgumentException(sprintf(
                'The Cin7 limit must be a whole number from 1 to %d, got %s.',
                self::LIMIT_MAX,
                self::describe($limit),
            ));
        }
    }

    /**
     * Describe a rejected value for the exception message.
     */
    private static function describe(mixed $value): string
    {
        return is_scalar($value) ? var_export($value, true) : get_debug_type($value);
    }
}
