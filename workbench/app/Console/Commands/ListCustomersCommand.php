<?php

declare(strict_types=1);

namespace Workbench\App\Console\Commands;

use Hypervel\Console\Command;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Workbench\App\Services\CustomerDirectory;

/**
 * Poke at the live Cin7 API by hand:
 *
 *     vendor/bin/testbench cin7:customers --limit=5
 *
 * The automated suite fakes every send, so nothing in it ever proves a real
 * request is accepted by Cin7. This is the escape hatch for that. It needs real
 * credentials in `workbench/.env` (CIN7_ACCOUNT_ID, CIN7_APPLICATION_KEY),
 * which is gitignored and must stay uncommitted; with the test ones it will
 * simply come back unauthorized, which is itself a useful signal that the
 * headers are going out.
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
            // AlwaysThrowOnErrors turns a non-2xx into an exception, so an
            // unauthorized or throttled response arrives here rather than as an
            // empty list — worth saying out loud instead of printing "0 rows".
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
