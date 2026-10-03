<?php

declare(strict_types=1);

use Hypervel\Database\Migrations\Migration;
use Hypervel\Database\Schema\Blueprint;
use Hypervel\Support\Facades\Schema;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\SyncConfig;

/*
 * The table the sync writes every Cin7 record to. Loaded only when `cin7.sync.enabled` is true.
 *
 * See docs/sync.md.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::connection(SyncConfig::connection())->create(Payload::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('account_id', 64);
            // The endpoint the payload came from, spelled as the request path: customer, saleList, sale.
            $table->string('module', 32);
            // The record's identifier under the key its module uses, which is not always ID.
            $table->string('cin7_id', 64);
            $table->jsonb('payload');
            // Cin7's own modified time: how a pull tells a changed record from one it read again.
            $table->timestamp('cin7_modified_at', 3)->nullable();
            // The last pull that returned the record, changed or not.
            $table->timestamp('synced_at');
            // updated_at moves only when the record changed.
            $table->timestamps();

            $table->unique(['account_id', 'module', 'cin7_id']);
            $table->index(['account_id', 'module', 'cin7_modified_at']);
            $table->index(['account_id', 'module', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::connection(SyncConfig::connection())->dropIfExists(Payload::TABLE);
    }
};
