<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_batch_id')->constrained('generation_batches')->cascadeOnDelete();
            $table->unsignedInteger('row_index');
            $table->json('input_data');
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->json('steps_output')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->unsignedInteger('execution_time_ms')->nullable();
            $table->unsignedInteger('tokens_used')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
