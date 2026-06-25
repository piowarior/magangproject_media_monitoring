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
        Schema::create('sentiments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('news_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete();

            $table->enum('final_sentiment', [
                'positive',
                'neutral',
                'negative'
            ]);

            $table->float('confidence_score');

            $table->string('model_version');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiments');
    }
};
