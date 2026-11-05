<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use InvalidArgumentException;
use Ipsocode\Cin7\PageDefaults;
use Ipsocode\Cin7\Support\ConfigRange;

/**
 * Reads `cin7.sync.*` each time it is asked, through the config repository's typed getters, and
 * rejects what is out of range. The shipped config holds every default.
 *
 * @see src/docs/sync.md
 */
class SyncConfig
{
    /**
     * Every module, in `sync.modules`.
     */
    public const string ALL = '*';

    /**
     * The largest failure count a `smallint` column holds on every database.
     */
    public const int MAX_FAILURES_MAX = 32767;

    /**
     * Tell whether the scheduled pulls are on.
     */
    public static function enabled(): bool
    {
        return config()->boolean('cin7.sync.enabled');
    }

    /**
     * Return the common time every module is pulled at; null schedules no common pull.
     */
    public static function cron(): ?string
    {
        return self::expression(config('cin7.sync.cron'));
    }

    /**
     * Return the modules pulled at the common time, in their configured order. `'*'`, the default, alone
     * or in a list, is every module, each list module before its documents.
     *
     * @return list<Module>
     */
    public static function modules(): array
    {
        return self::resolve((array) (config('cin7.sync.modules') ?? throw new InvalidArgumentException(
            'Configuration value for key [cin7.sync.modules] must be a string or an array, NULL given.',
        )));
    }

    /**
     * Resolve the modules a list of names stands for: `'*'` among them is every module.
     *
     * @param array<array-key, int|string> $names
     * @return list<Module>
     */
    public static function resolve(array $names): array
    {
        $names = array_values($names);

        return in_array(self::ALL, $names, true) ? Module::cases() : array_map(self::module(...), $names);
    }

    /**
     * Return the modules the common pull takes: those that read only what changed. A reference book,
     * read whole, waits for the full pull or `cin7:sync`.
     *
     * @return list<Module>
     */
    public static function scheduled(): array
    {
        return array_values(array_filter(self::modules(), static fn (Module $module): bool => $module->incremental()));
    }

    /**
     * Return each module pulled at a time of its own as well, with that time. A reference book cannot
     * be one: it is read whole, so only the full pull and `cin7:sync` read it.
     *
     * @return list<array{Module, string}>
     */
    public static function exceptions(): array
    {
        $exceptions = [];

        foreach (config()->array('cin7.sync.exceptions') as $name => $cron) {
            $module = self::module($name);
            $expression = self::expression($cron);

            if (! $module->incremental()) {
                throw new InvalidArgumentException(sprintf(
                    'cin7.sync.exceptions names [%s], which Cin7 can only send whole. The full pull reads it, and cin7:sync on demand.',
                    $module->value,
                ));
            }

            if ($expression !== null) {
                $exceptions[] = [$module, $expression];
            }
        }

        return $exceptions;
    }

    /**
     * Return the time of the full pull; null schedules none.
     */
    public static function full(): ?string
    {
        return self::expression(config('cin7.sync.full'));
    }

    /**
     * Return the minutes an incremental pull reaches back, at least 0.
     *
     * @throws InvalidArgumentException when the configured value is below 0
     */
    public static function lookback(): int
    {
        return ConfigRange::integer('cin7.sync.lookback', 0);
    }

    /**
     * Return the records a page holds, from 1 to the largest page Cin7 serves.
     *
     * @throws InvalidArgumentException when the configured value is outside that range
     */
    public static function limit(): int
    {
        return ConfigRange::integer('cin7.sync.limit', 1, PageDefaults::LIMIT_MAX);
    }

    /**
     * Return the milliseconds between the sync's own calls, at least 0.
     *
     * @throws InvalidArgumentException when the configured value is below 0
     */
    public static function pauseMs(): int
    {
        return ConfigRange::integer('cin7.sync.pause_ms', 0);
    }

    /**
     * Return the documents read per module per run, at least 0.
     *
     * @throws InvalidArgumentException when the configured value is below 0
     */
    public static function documents(): int
    {
        return ConfigRange::integer('cin7.sync.documents', 0);
    }

    /**
     * Return the failed reads after which a document is no longer tried, from 1 to the largest a `smallint`
     * column holds on every database.
     *
     * @throws InvalidArgumentException when the configured value is outside that range
     */
    public static function maxFailures(): int
    {
        return ConfigRange::integer('cin7.sync.max_failures', 1, self::MAX_FAILURES_MAX);
    }

    /**
     * Return the queue a pull runs on; null uses the connection's default.
     */
    public static function queue(): ?string
    {
        return self::name(config('cin7.sync.queue'));
    }

    /**
     * Return the seconds a queued pull may run, at least 1.
     *
     * @throws InvalidArgumentException when the configured value is below 1
     */
    public static function timeout(): int
    {
        return ConfigRange::integer('cin7.sync.timeout', 1);
    }

    /**
     * Return the queue connection a pull runs on; null uses the default one.
     */
    public static function connection(): ?string
    {
        return self::name(config('cin7.sync.connection'));
    }

    /**
     * Resolve the module a configured name stands for; an unknown one fails loudly rather than being
     * skipped, so a typo never silently stops a module syncing.
     */
    public static function module(int|string $name): Module
    {
        return Module::tryFrom((string) $name) ?? throw new InvalidArgumentException(sprintf(
            'cin7.sync names the module [%s], which is not one of: %s.',
            $name,
            implode(', ', array_column(Module::cases(), 'value')),
        ));
    }

    private static function expression(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }

    private static function name(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
