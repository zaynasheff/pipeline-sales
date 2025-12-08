<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tenantEnabled = config('pipeline-sales.enable_multitenancy');
        $tenantKey = config('pipeline-sales.tenant.foreign_key', 'company_id');

        Schema::create('deals', function (Blueprint $table) use ($tenantEnabled, $tenantKey) {

            $table->uuid()->primary();
            $table->uuid('stage_uuid');
            // Tenant key for multitenancy
            if ($tenantEnabled) {
                $table->foreignId($tenantKey)
                    ->constrained(config('pipeline-sales.tenant.table'))
                    ->cascadeOnDelete();
            }

            // Foreign key to stage
            $table
                ->foreign('stage_uuid')
                ->references('uuid')
                ->on('stages')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->tinyInteger('priority')->default(2);
            $table->json('tags')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
