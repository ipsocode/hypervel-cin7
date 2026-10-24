<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Console;

use Hypervel\Console\Command;
use Hypervel\RateLimiter\Cooldown;
use Hypervel\RateLimiter\RateLimiter;
use Hypervel\Saloon\Exceptions\Request\FatalRequestException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use InvalidArgumentException;
use Ipsocode\Cin7\Cin7Manager;
use Ipsocode\Cin7\Data\Me\MeData;
use Throwable;

/**
 * Proves a connection's credentials and limiter store with one `GET me`, the cheapest call Cin7
 * authenticates. It prints neither credential.
 *
 * @see src/docs/connector.md
 */
class CheckCommand extends Command
{
    protected ?string $signature = 'cin7:check
        {--connection= : The connection to check (default: cin7.default)}
        {--json : Print the result as one JSON object, for a health check}';

    protected string $description = 'Check a Cin7 connection\'s credentials and limiter store';

    /**
     * @var array<string, string>
     */
    private const array LABELS = [
        'connection' => 'Connection',
        'rate_limit' => 'Rate limit',
        'limiter_store' => 'Limiter store',
        'company' => 'Company',
        'currency' => 'Currency',
        'time_zone' => 'Time zone',
    ];

    public function handle(Cin7Manager $manager, RateLimiter $limiter): int
    {
        $name = $this->option('connection');
        $name = is_string($name) && $name !== '' ? $name : $manager->defaultConnectionName();
        $details = ['connection' => $name];

        try {
            $cin7 = $manager->connection($name);
        } catch (InvalidArgumentException $exception) {
            return $this->finish($details, $exception->getMessage());
        }

        $window = $cin7->rateLimitWindow();
        $store = (string) ($cin7->rateLimitStore() ?? config('saloon.rate_limiter.store') ?? $limiter->getDefaultInstance());
        $details['rate_limit'] = $window === null ? 'off' : "{$window[0]} calls per {$window[1]}s";
        $details['limiter_store'] = $store;

        // Inspecting a cooldown reaches the store without taking from any window, so a store the
        // worker cannot reach is named here, before a send would throw from it.
        try {
            $limiter->store($store)->inspect(Cooldown::for('cin7:check'));
        } catch (Throwable $exception) {
            return $this->finish($details, "The limiter store [{$store}] failed: {$exception->getMessage()}");
        }

        try {
            /** @var MeData $me */
            $me = $cin7->me()->get()->dto();
        } catch (RequestException $exception) {
            $status = $exception->status();
            $text = $exception->response()->json('Exception');

            return $this->finish($details, match (true) {
                $status === 403 => 'Cin7 rejected the credentials (403).',
                is_string($text) => "Cin7 answered {$status}: {$text}",
                default => "Cin7 answered {$status}.",
            });
        } catch (FatalRequestException $exception) {
            return $this->finish($details, "Could not reach Cin7: {$exception->getMessage()}");
        }

        return $this->finish($details + [
            'company' => (string) $me->Company,
            'currency' => (string) $me->Currency,
            'time_zone' => (string) $me->TimeZone,
        ]);
    }

    /**
     * Print what was found, and the error if a step failed, then exit 0 only without one.
     *
     * @param array<string, string> $details
     */
    private function finish(array $details, ?string $error = null): int
    {
        if ($this->option('json')) {
            $this->line((string) json_encode(
                ['ok' => $error === null] + $details + ($error === null ? [] : ['error' => $error]),
                JSON_UNESCAPED_SLASHES,
            ));
        } else {
            foreach ($details as $key => $value) {
                $this->components->twoColumnDetail(self::LABELS[$key], $value);
            }

            $error === null
                ? $this->components->info('Cin7 accepted the credentials.')
                : $this->components->error($error);
        }

        return $error === null ? self::SUCCESS : self::FAILURE;
    }
}
