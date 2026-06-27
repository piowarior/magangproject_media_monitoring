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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('source_id')
                ->constraid('news_sources')
                ->nullOnDelete();
            $table->string('title');
            $table->LongText('content')->nullable();
            $table->text('url');
            $table->timestamp('published_at')->nullable();
            $table->string('hash')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
