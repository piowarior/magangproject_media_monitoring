<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')
                ->constrained('news_sources')
                ->cascadeOnDelete();
            $table->date('period_date'); // ranking dihitung per hari
            $table->unsignedInteger('total_news')->default(0);
            $table->float('positive_ratio')->default(0); // 0.0 - 1.0
            $table->float('negative_ratio')->default(0);
            $table->float('score')->default(0);          // skor ranking akhir
            $table->unique(['source_id', 'period_date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_rankings');
    }
};
