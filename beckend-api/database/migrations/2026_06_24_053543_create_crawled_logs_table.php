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
        Schema::create('crawled_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('status', ['success', 'fail']);
            $table->integer('total_fetched')->default(0);
            $table->integer('total_saved')->default(0);
            // FIX: was integer type + typo 'erorr_message' → text 'error_message'
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawled_logs');
    }
};
