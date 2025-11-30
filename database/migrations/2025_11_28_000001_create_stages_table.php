<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tenantEnabled = config('pipeline-sales.enable_multitenancy');
        $tenantKey = config('pipeline-sales.tenant.foreign_key', 'company_id');

        Schema::create('stages', function (Blueprint $table) use ($tenantEnabled, $tenantKey) {
            $table->id();

            // Tenant key for multitenancy
            if ($tenantEnabled) {
                $table->foreignId($tenantKey)
                    ->constrained(config('pipeline-sales.tenant.table'))
                    ->cascadeOnDelete();
            }

            // Foreign key to the pipeline
            $table->foreignId('pipeline_id')->constrained('pipelines')->cascadeOnDelete();

            $table->string('name');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
