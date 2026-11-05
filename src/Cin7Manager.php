<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use InvalidArgumentException;
use Ipsocode\Cin7\Support\RetrySettings;

/**
 * Resolves the named Cin7 connections of `config('cin7')`, one connector each.
 *
 * A connector is built the first time its name is asked for and kept in a map, so one instance
 * serves every coroutine in a worker. Building reads config and does no I/O, so two coroutines
 * cannot interleave inside it, and the connectors are immutable, so sharing them is safe.
 *
 * @see src/docs/connector.md
 * @see src/docs/configuration.md
 */
class Cin7Manager
{
    /**
     * @var array<string, Cin7Connector>
     */
    private array $connectors = [];

    /**
     * Return the connector for a connection, the configured default one when no name is given.
     *
     * @throws InvalidArgumentException when no such connection is configured
     */
    public function connection(?string $name = null): Cin7Connector
    {
        $name ??= $this->defaultConnectionName();

        return $this->connectors[$name] ??= $this->build($name);
    }

    /**
     * Return the name `connection()` resolves without one: `cin7.default`.
     */
    public function defaultConnectionName(): string
    {
        return config()->string('cin7.default');
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
        // A blank store, such as `CIN7_RATE_STORE=` in `.env`, is left out too; one that is still
        // blank is unset, so the connector falls back to `saloon.rate_limiter.store`.
        $store = array_key_exists('store', $rateLimit) && $rateLimit['store'] !== '' ? $rateLimit['store'] : config('cin7.rate_limit.store');

        if ($store === '') {
            $store = null;
        }

        $timeout = $this->transportSetting($connection, 'timeout');
        $connectTimeout = $this->transportSetting($connection, 'connect_timeout');

        return new Cin7Connector(
            $this->credential($name, $connection, 'account_id', 'CIN7_ACCOUNT_ID'),
            $this->credential($name, $connection, 'application_key', 'CIN7_APPLICATION_KEY'),
            (int) ($rateLimit['max'] ?? config()->integer('cin7.rate_limit.max')),
            (int) ($rateLimit['period'] ?? config()->integer('cin7.rate_limit.period')),
            $store === null ? null : (string) $store,
            (int) ($rateLimit['cooldown'] ?? config()->integer('cin7.rate_limit.cooldown')),
            (string) $this->transportSetting($connection, 'base_url'),
            $timeout === null ? null : (int) $timeout,
            $connectTimeout === null ? null : (int) $connectTimeout,
            RetrySettings::fromConfig(),
        );
    }

    /**
     * Resolve a connection's required credential, which fails at resolve time rather than as a 403 on
     * the first request when it is missing or blank.
     *
     * @param array<mixed> $connection
     *
     * @throws InvalidArgumentException when the credential is null or blank
     */
    private function credential(string $name, array $connection, string $key, string $env): string
    {
        $value = $connection[$key] ?? null;

        if ($value === null || trim((string) $value) === '') {
            $hint = $name === 'default'
                ? "set {$env}"
                : "set cin7.connections.{$name}.{$key}";

            throw new InvalidArgumentException("Cin7 connection [{$name}] has no {$key}; {$hint}.");
        }

        return (string) $value;
    }

    /**
     * Resolve a transport key, the connection's own before the top-level one. A blank value, such as
     * `CIN7_TIMEOUT=` in `.env`, counts as unset: cast, it would be a timeout of 0, which waits
     * forever, or an empty base URL.
     *
     * @param array<mixed> $connection
     */
    private function transportSetting(array $connection, string $key): int|string|null
    {
        $value = $connection[$key] ?? null;

        if ($value === null || $value === '') {
            $value = config('cin7.' . $key);
        }

        return $value === '' ? null : $value;
    }
}
