<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_topics', function (Blueprint $table) {
            $table->foreignId('news_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('topic_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->primary(['news_id', 'topic_id']); // composite PK (bukan id)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_topics');
    }
};
