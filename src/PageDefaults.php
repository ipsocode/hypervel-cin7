<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

/**
 * The `Helper::prepareParameters()` port from `eighteen73/dear-api`.
 *
 * Cin7 requires `page` and `limit` on every read; upstream injected them into
 * get, find *and* delete query strings — but never into create/update bodies.
 * The lowercase spelling is deliberate: Cin7 also accepts `Page`/`Limit`, and
 * callers that paginate themselves send both casings today.
 */
final class PageDefaults
{
    public const int PAGE = 1;

    public const int LIMIT = 100;

    /**
     * Add the page and limit defaults, leaving caller values alone.
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
