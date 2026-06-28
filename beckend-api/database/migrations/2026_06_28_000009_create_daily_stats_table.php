<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('total_news')->default(0);
            $table->unsignedInteger('positive')->default(0);
            $table->unsignedInteger('neutral')->default(0);
            $table->unsignedInteger('negative')->default(0);
            $table->unique(['keyword_id', 'date']); // 1 keyword 1 tanggal = 1 record
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stats');
    }
};
