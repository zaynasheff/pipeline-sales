<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal_user', function (Blueprint $table) {
            $table->uuid('deal_uuid');
            $table
                ->foreign('deal_uuid')
                ->references('uuid')
                ->on('deals')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('user_id');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->primary(['deal_uuid', 'user_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('deal_user');
    }
};
