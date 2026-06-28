<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // "Ahmad Rois", "DPRD Banten"
            $table->enum('type', [
                'person',                        // nama orang
                'organization',                  // nama instansi/lembaga
                'place',                         // nama tempat
            ]);
            $table->unique(['name', 'type']);    // tidak boleh duplikat nama + tipe
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
