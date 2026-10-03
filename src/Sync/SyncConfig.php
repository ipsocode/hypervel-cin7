<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use InvalidArgumentException;
use Ipsocode\Cin7\PageDefaults;

/**
 * Reads `cin7.sync.*` each time it is asked, casting and clamping rather than trusting the type,
 * as the provider does for the connector's settings.
 *
 * @see docs/sync.md
 */
final class SyncConfig
{
    /**
     * Every module, in `sync.modules`.
     */
    public const string ALL = '*';

    public static function enabled(): bool
    {
        return (bool) config('cin7.sync.enabled', false);
    }

    /**
     * The common time every module is pulled at; null schedules no common pull.
     */
    public static function cron(): ?string
    {
        return self::expression(config('cin7.sync.cron'));
    }

    /**
     * The modules pulled at the common time, in their configured order. `'*'`, the default, alone
     * or in a list, is every module, each list module before its documents.
     *
     * @return list<Module>
     */
    public static function modules(): array
    {
        return self::resolve((array) (config('cin7.sync.modules') ?? self::ALL));
    }

    /**
     * The modules a list of names stands for: `'*'` among them is every module.
     *
     * @param array<array-key, mixed> $names
     * @return list<Module>
     */
    public static function resolve(array $names): array
    {
        $names = array_values($names);

        return in_array(self::ALL, $names, true) ? Module::cases() : array_map(self::module(...), $names);
    }

    /**
     * The modules the common pull takes: those that read only what changed. A reference book,
     * read whole, waits for the full pull or `cin7:sync`.
     *
     * @return list<Module>
     */
    public static function scheduled(): array
    {
        return array_values(array_filter(self::modules(), static fn (Module $module): bool => $module->incremental()));
    }

    /**
     * Each module pulled at a time of its own as well, with that time. A reference book cannot
     * be one: it is read whole, so only the full pull and `cin7:sync` read it.
     *
     * @return list<array{Module, string}>
     */
    public static function exceptions(): array
    {
        $exceptions = [];

        foreach ((array) config('cin7.sync.exceptions', []) as $name => $cron) {
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
     * The time of the full pull; null schedules none.
     */
    public static function full(): ?string
    {
        return self::expression(config('cin7.sync.full'));
    }

    /**
     * Minutes an incremental pull reaches back, at least 0.
     */
    public static function lookback(): int
    {
        return max(0, (int) (config('cin7.sync.lookback') ?? 1440));
    }

    /**
     * Records a page, from 1 to the largest page Cin7 serves.
     */
    public static function limit(): int
    {
        return min(PageDefaults::LIMIT_MAX, max(1, (int) (config('cin7.sync.limit') ?? 500)));
    }

    public static function pauseMs(): int
    {
        return max(0, (int) (config('cin7.sync.pause_ms') ?? 1000));
    }

    /**
     * Documents read per module per run, at least 0.
     */
    public static function documents(): int
    {
        return max(0, (int) (config('cin7.sync.documents') ?? 250));
    }

    public static function queue(): ?string
    {
        return self::name(config('cin7.sync.queue'));
    }

    /**
     * Seconds a queued pull may run, at least 1.
     */
    public static function timeout(): int
    {
        return max(1, (int) (config('cin7.sync.timeout') ?? 3600));
    }

    public static function connection(): ?string
    {
        return self::name(config('cin7.sync.connection'));
    }

    /**
     * The module a configured name stands for; an unknown one fails loudly rather than being
     * skipped, so a typo never silently stops a module syncing.
     */
    public static function module(mixed $name): Module
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
