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
        Schema::create('keyword_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('triggered_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            // FIX: enum values had typos ('procesing', 'erorr')
            $table->enum('status', ['processing', 'done', 'error'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_runs');
    }
};
