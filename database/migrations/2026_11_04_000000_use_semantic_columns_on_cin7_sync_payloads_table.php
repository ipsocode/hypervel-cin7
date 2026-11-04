<?php

declare(strict_types=1);

use Hypervel\Database\Migrations\Migration;
use Hypervel\Database\Schema\Blueprint;
use Hypervel\Support\Facades\Schema;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\SyncConfig;

/*
 * Gives the table's columns the types their values call for: `account_id` is a GUID, `module`
 * and `cin7_id` take no invented length, and `cin7_modified_at` is Cin7's own date, so it is a
 * `datetime`, with no `TIMESTAMP` session time zone conversion on MySQL and no 2038 limit.
 * Loaded only when `cin7.sync.enabled` is true.
 *
 * On PostgreSQL the `account_id` cast fails on a value that is not a GUID, so the configured
 * `account_id` of every connection the sync has pulled must be one.
 *
 * See src/docs/sync.md.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            $table->uuid('account_id')->using('account_id::uuid')->change();
            $table->string('module')->change();
            $table->string('cin7_id')->change();
            $table->dateTime('cin7_modified_at', 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::connection(SyncConfig::connection())->table(Payload::TABLE, function (Blueprint $table) {
            $table->string('account_id', 64)->change();
            $table->string('module', 32)->change();
            $table->string('cin7_id', 64)->change();
            $table->timestamp('cin7_modified_at', 3)->nullable()->change();
        });
    }
};
