<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_model_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')
                ->unique()          // 1 berita hanya punya 1 log AI
                ->constrained()
                ->cascadeOnDelete();
            // Skor tiap model (0.0 - 1.0, makin tinggi = makin positif)
            $table->float('model_a_score')->nullable(); // Lexicon-based
            $table->float('model_b_score')->nullable(); // ML (SVM/Naive Bayes)
            $table->float('model_c_score')->nullable(); // DL (IndoBERT)
            $table->float('final_score')->nullable();   // Hasil ensemble
            $table->unsignedSmallInteger('processing_time_ms')->nullable(); // Lama proses
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_model_logs');
    }
};
