<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use Hypervel\Data\Data;
use Hypervel\Database\Eloquent\Builder;
use Hypervel\Database\Eloquent\Model;
use Hypervel\Support\Carbon;
use Ipsocode\Cin7\Cin7Connector;

/**
 * One synced Cin7 record: a list row or a full document, as Cin7 returned it.
 *
 * @property int $id
 * @property string $account_id
 * @property string $module
 * @property string $cin7_id
 * @property array<string, mixed> $payload
 * @property null|Carbon $cin7_modified_at
 * @property Carbon $synced_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @see docs/sync.md
 */
final class Payload extends Model
{
    public const string TABLE = 'cin7_sync_payloads';

    protected ?string $table = self::TABLE;

    protected array $guarded = [];

    public function getConnectionName(): ?string
    {
        return SyncConfig::connection();
    }

    /**
     * One module's records for one account: by default the account the connector calls.
     *
     * @return Builder<self>
     */
    public static function forModule(Module|string $module, ?string $accountId = null): Builder
    {
        return self::query()
            ->where('account_id', $accountId ?? app(Cin7Connector::class)->accountId())
            ->where('module', $module instanceof Module ? $module->value : $module);
    }

    /**
     * The payload as its module's data object, such as a `CustomerData` or a `SaleData`.
     */
    public function data(): Data
    {
        return SyncConfig::module($this->module)->dataClass()::from($this->payload);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'cin7_modified_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }
}
