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
        Schema::create('generation_batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_filename');
            $table->string('file_path');
            $table->json('prompt_sequence');
            $table->string('status')->default('pending');
            $table->unsignedInteger('total_tasks')->default(0);
            $table->unsignedInteger('processed_tasks')->default(0);
            $table->unsignedInteger('successful_tasks')->default(0);
            $table->unsignedInteger('failed_tasks')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generation_batches');
    }
};
