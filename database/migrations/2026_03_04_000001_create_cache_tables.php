<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }
    }

    public function down(): void
    {
        // Only drop tables if they exist AND were created by this migration.
        // In practice on Laravel 12, `cache` may be owned by the core migration,
        // so we only drop if present (safe for local dev).
        if (Schema::hasTable('cache_locks')) {
            Schema::dropIfExists('cache_locks');
        }
        // Do not drop `cache` automatically to avoid deleting core tables unexpectedly.
    }
};
