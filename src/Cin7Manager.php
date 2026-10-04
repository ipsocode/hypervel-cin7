<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use InvalidArgumentException;

/**
 * Resolves the named Cin7 connections of `config('cin7')`, one connector each.
 *
 * A connector is built the first time its name is asked for and kept in a map, so one instance
 * serves every coroutine in a worker. Building reads config and does no I/O, so two coroutines
 * cannot interleave inside it, and the connectors are immutable, so sharing them is safe.
 *
 * @see docs/connector.md
 * @see docs/configuration.md
 */
final class Cin7Manager
{
    /**
     * @var array<string, Cin7Connector>
     */
    private array $connectors = [];

    /**
     * The connector for a connection, the configured default one when no name is given.
     *
     * @throws InvalidArgumentException when no such connection is configured
     */
    public function connection(?string $name = null): Cin7Connector
    {
        $name ??= $this->defaultConnectionName();

        return $this->connectors[$name] ??= $this->build($name);
    }

    /**
     * The name `connection()` resolves without one: `cin7.default`.
     */
    public function defaultConnectionName(): string
    {
        return (string) config('cin7.default', 'default');
    }

    private function build(string $name): Cin7Connector
    {
        $connection = config('cin7.connections.' . $name);

        // The top-level keys are the implicit `default` connection, so an unconfigured
        // application keeps working on the keys it already has.
        if ($connection === null && $name === 'default') {
            $connection = [
                'account_id' => config('cin7.account_id'),
                'application_key' => config('cin7.application_key'),
            ];
        }

        if (! is_array($connection)) {
            throw new InvalidArgumentException("Cin7 connection [{$name}] is not configured: add it to `cin7.connections`.");
        }

        // A key the connection leaves out of `rate_limit` is the top-level one, and the casts
        // stop unset CIN7_* values (null) and string numbers from a TypeError on resolve.
        $rateLimit = is_array($connection['rate_limit'] ?? null) ? $connection['rate_limit'] : [];
        $store = array_key_exists('store', $rateLimit) ? $rateLimit['store'] : config('cin7.rate_limit.store');

        return new Cin7Connector(
            (string) ($connection['account_id'] ?? ''),
            (string) ($connection['application_key'] ?? ''),
            (int) ($rateLimit['max'] ?? config('cin7.rate_limit.max', 60)),
            (int) ($rateLimit['period'] ?? config('cin7.rate_limit.period', 60)),
            $store === null ? null : (string) $store,
        );
    }
}
