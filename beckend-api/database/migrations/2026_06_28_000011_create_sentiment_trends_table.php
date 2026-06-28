<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentiment_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('date');
            // JSON: { "positive": 0.45, "neutral": 0.35, "negative": 0.20 }
            $table->json('sentiment_distribution');
            $table->unique(['keyword_id', 'date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_trends');
    }
};
