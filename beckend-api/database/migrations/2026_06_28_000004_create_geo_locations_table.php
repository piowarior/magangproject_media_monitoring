<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')
                ->unique()          // 1 region hanya punya 1 titik koordinat
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('lat', 10, 7);   // latitude (misal: -6.1234567)
            $table->decimal('lng', 10, 7);   // longitude (misal: 106.1234567)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_locations');
    }
};
