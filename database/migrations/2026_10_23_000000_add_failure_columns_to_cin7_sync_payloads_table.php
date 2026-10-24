<?php

declare(strict_types=1);

use Hypervel\Database\Migrations\Migration;
use Hypervel\Database\Schema\Blueprint;
use Hypervel\Support\Facades\Schema;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\SyncConfig;

/*
 * Where a list row records that its document keeps failing to read. Loaded only when
 * `cin7.sync.enabled` is true.
 *
 * See src/docs/sync.md.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            // Times the row's document read failed since the row last changed.
            $table->unsignedSmallInteger('failures')->default(0);
            // The last of those failures.
            $table->timestamp('failed_at')->nullable();
            // That failure's message, cut to fit.
            $table->string('last_error', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            $table->dropColumn(['failures', 'failed_at', 'last_error']);
        });
    }
};
