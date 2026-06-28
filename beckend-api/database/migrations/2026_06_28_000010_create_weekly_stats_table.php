<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('week_start');
            $table->date('week_end');
            $table->unsignedInteger('total_news')->default(0);
            $table->unsignedInteger('positive')->default(0);
            $table->unsignedInteger('neutral')->default(0);
            $table->unsignedInteger('negative')->default(0);
            $table->text('summary')->nullable(); // ringkasan teks otomatis
            $table->unique(['keyword_id', 'week_start']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_stats');
    }
};
