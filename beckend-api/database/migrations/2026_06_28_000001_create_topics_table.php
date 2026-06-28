<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Politik, Ekonomi, Hukum, dll
            $table->string('slug')->unique(); // politik, ekonomi, hukum
            $table->string('color')->default('#6B7280'); // warna badge di UI
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
