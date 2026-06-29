<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keyword_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // contoh: "DPRD Banten Group"
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Pivot: satu group bisa punya banyak keyword
        Schema::create('keyword_group_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_group_id')->constrained('keyword_groups')->cascadeOnDelete();
            $table->foreignId('keyword_id')->constrained('keywords')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['keyword_group_id', 'keyword_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_group_items');
        Schema::dropIfExists('keyword_groups');
    }
};
