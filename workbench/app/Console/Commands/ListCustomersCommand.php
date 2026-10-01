<?php

declare(strict_types=1);

namespace Workbench\App\Console\Commands;

use Hypervel\Console\Command;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Workbench\App\Services\CustomerDirectory;

/**
 * Lists customers from the live Cin7 API: `vendor/bin/testbench cin7:customers --limit=5`.
 *
 * It needs real credentials in the gitignored `workbench/.env`; the test ones get a 403.
 *
 * @see docs/testing.md
 */
class ListCustomersCommand extends Command
{
    protected ?string $signature = 'cin7:customers
        {--limit=10 : How many customers to ask Cin7 for}
        {--name= : Filter by customer name}';

    protected string $description = 'List Cin7 customers through the package connector';

    public function handle(CustomerDirectory $customers): int
    {
        $filters = ['limit' => (int) $this->option('limit')];

        if ($name = $this->option('name')) {
            $filters['Name'] = $name;
        }

        try {
            $rows = $customers->all($filters);
        } catch (RequestException $exception) {
            // AlwaysThrowOnErrors throws on any non-2xx, so a 403 or 503 lands here
            // rather than as an empty list.
            $this->error(sprintf('Cin7 responded %d: %s', $exception->status(), $exception->getMessage()));

            return self::FAILURE;
        }

        if ($rows === []) {
            $this->info('No customers returned.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Name', 'Status'],
            array_map(
                fn (array $row): array => [$row['ID'] ?? '', $row['Name'] ?? '', $row['Status'] ?? ''],
                $rows,
            ),
        );

        return self::SUCCESS;
    }
}
