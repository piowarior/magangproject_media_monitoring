<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->boolean('is_duplicate')->default(false)->after('url');
            $table->foreignId('duplicate_of')->nullable()->after('is_duplicate')
                  ->constrained('news')->nullOnDelete();
            $table->boolean('is_relevant')->default(true)->after('duplicate_of');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['duplicate_of']);
            $table->dropColumn(['is_duplicate', 'duplicate_of', 'is_relevant']);
        });
    }
};
