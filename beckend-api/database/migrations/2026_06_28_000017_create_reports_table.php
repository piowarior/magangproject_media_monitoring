<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')
                ->constrained()
                ->restrictOnDelete(); // laporan tidak boleh ikut terhapus
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->string('title');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['draft', 'generated', 'exported'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
