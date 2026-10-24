<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests;

use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * The catalogue rows, one file per API path under `tests/Fixtures/Catalogue/`
 * (`sale/invoice.php` holds the rows for `sale/invoice`). Each file returns its rows by kind:
 *
 * - `requests`: one per request class, plus `… with data` rows for data object bodies
 * - `resources`: one per resource method, keyed `<resource path> <method>`
 * - `dtos`: the request, its fixture and the data class `dto()` returns
 * - `bodies`: a body class and the reference's request example it round-trips
 * - `missing`: a class and a payload without one of its required fields
 * - `required`: the fields of a class the reference requires
 * - `omitted`: a request, the body given and the body sent without its `$omit` fields
 *
 * @see docs/testing.md
 */
final class Catalogue
{
    /**
     * How many shards the big catalogue test classes split their rows into, so the parallel run
     * can spread them over its workers.
     */
    public const int SHARDS = 4;

    /**
     * Every file's rows, by kind; read once per process.
     *
     * @var null|array<string, array<array-key, mixed>>
     */
    private static ?array $rows = null;

    /**
     * The rows of one kind from every file, keyed as the files key them.
     *
     * @return array<array-key, mixed>
     */
    public static function rows(string $kind): array
    {
        return (self::$rows ??= self::load())[$kind] ?? [];
    }

    /**
     * One shard of the rows of one kind: those whose key hashes to it, so every row is in exactly one shard.
     *
     * @return array<array-key, mixed>
     */
    public static function shard(string $kind, int $shard): array
    {
        return array_filter(
            self::rows($kind),
            static fn (int|string $key): bool => crc32((string) $key) % self::SHARDS === $shard,
            ARRAY_FILTER_USE_KEY,
        );
    }

    /**
     * Merge the files' rows by kind; a key two files share is a mistake, so it throws.
     *
     * @return array<string, array<array-key, mixed>>
     */
    private static function load(): array
    {
        $root = __DIR__ . '/Fixtures/Catalogue';
        $files = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        $rows = [];
        $origins = [];

        foreach ($files as $file) {
            foreach (self::read($file) as $kind => $entries) {
                foreach ($entries as $key => $row) {
                    if (isset($rows[$kind][$key])) {
                        throw new LogicException("The {$kind} row '{$key}' is in both {$origins[$kind][$key]} and {$file}.");
                    }

                    $rows[$kind][$key] = $row;
                    $origins[$kind][$key] = $file;
                }
            }
        }

        return $rows;
    }

    /**
     * Require one file in a scope of its own, so the local variables it defines stay its own.
     *
     * @return array<string, array<array-key, mixed>>
     */
    private static function read(string $file): array
    {
        return require $file;
    }
}
