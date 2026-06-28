<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_items', function (Blueprint $table) {
            $table->foreignId('report_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('news_id')
                ->constrained()
                ->restrictOnDelete(); // berita tidak boleh dihapus kalau masih di laporan
            $table->primary(['report_id', 'news_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};
