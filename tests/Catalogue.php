<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests;

use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * The catalog rows, one file per API path under `tests/Fixtures/Catalogue/`
 * (`sale/invoice.php` holds the rows for `sale/invoice`). Each file returns its rows by kind:
 *
 * - `requests`: one per request class, plus a `… with data` row for each write request, which sends a data object body
 * - `resources`: one per resource method, keyed `<resource path> <method>`. A row is the call, then the
 *   request class, verb, path, query and body it sends, or only the call and the key of the
 *   `requests` row that expects the same, which the loader fills in
 * - `dtos`: the request, its fixture and the data class `dto()` returns
 * - `bodies`: a body class, the write request that sends it and the reference's request example it round-trips
 * - `missing`: a class and a payload without one of its required fields
 * - `required`: the fields of a class the reference requires
 * - `omitted`: a request, the body given and the body sent without its `$omit` fields
 *
 * @see src/docs/testing.md
 */
final class Catalogue
{
    /**
     * How many shards the big catalog test classes split their rows into, so the parallel run
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

        foreach ($rows['resources'] ?? [] as $key => $row) {
            if (count($row) === 2) {
                $rows['resources'][$key] = self::expand($key, $row, $rows['requests'] ?? []);
            }
        }

        return $rows;
    }

    /**
     * A `resources` row that names a `requests` row: the resource's call with that row's request
     * class, verb, path, query and body.
     *
     * @param array{callable, string} $row
     * @param array<array-key, mixed> $requests
     * @return array<array-key, mixed>
     */
    private static function expand(string $key, array $row, array $requests): array
    {
        [$call, $request] = $row;

        $source = $requests[$request] ?? throw new LogicException("The resources row '{$key}' names the requests row '{$request}', which does not exist.");

        return [$call, $source[0], $source[2], $source[3], $source[4], $source[5]];
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
