<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('exported_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->enum('format', ['pdf', 'excel'])->default('pdf');
            $table->string('file_path')->nullable(); // path file di storage
            $table->timestamp('exported_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
    }
};
