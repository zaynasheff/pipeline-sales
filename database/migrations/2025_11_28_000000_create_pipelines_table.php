<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tenantEnabled = config('pipeline-sales.multitenancy');
        $tenantTable = config('pipeline-sales.tenant.table');
        $tenantKey = config('pipeline-sales.tenant.foreign_key');

        Schema::create('pipelines', function (Blueprint $table) use ($tenantEnabled, $tenantTable, $tenantKey) {

            $table->uuid()->primary();
            // Add tenant relation only if multitenancy is enabled
            if ($tenantEnabled) {
                $table->foreignId($tenantKey)
                    ->constrained($tenantTable)
                    ->cascadeOnDelete();
            }

            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        $tenantEnabled = config('pipeline-sales.multitenancy');
        $tenantKey = config('pipeline-sales.tenant.foreign_key');

        Schema::table('pipelines', function (Blueprint $table) use ($tenantEnabled, $tenantKey) {
            if ($tenantEnabled) {
                $table->dropColumn($tenantKey);
            }
        });

        Schema::dropIfExists('pipelines');
    }
};
