<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heatmap_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('date');
            $table->float('intensity_score')->default(0); // 0.0 - 1.0
            // Dominan sentimen hari itu di wilayah ini
            $table->enum('dominant_sentiment', [
                'positive', 'neutral', 'negative'
            ])->default('neutral');
            $table->unsignedInteger('total_news')->default(0);
            $table->unique(['region_id', 'date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heatmap_data');
    }
};
