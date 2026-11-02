<?php

declare(strict_types=1);

use Hypervel\Database\Migrations\Migration;
use Hypervel\Database\Schema\Blueprint;
use Hypervel\Support\Facades\Schema;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\SyncConfig;

/*
 * Lets `last_error` hold a failure's whole message, which the sync no longer cuts. Loaded only
 * when `cin7.sync.enabled` is true.
 *
 * See src/docs/sync.md.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            $table->text('last_error')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            $table->string('last_error', 255)->nullable()->change();
        });
    }
};
