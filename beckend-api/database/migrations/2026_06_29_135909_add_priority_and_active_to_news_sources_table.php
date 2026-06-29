<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_sources', function (Blueprint $table) {
            $table->integer('priority')->default(10)->after('base_url');  // makin kecil makin prioritas
            $table->boolean('is_active')->default(true)->after('priority');
            $table->string('source_type')->default('rss')->after('is_active'); // rss, api, scraper
            $table->timestamp('last_crawled_at')->nullable()->after('source_type');
            $table->integer('crawl_interval_minutes')->default(60)->after('last_crawled_at');
        });
    }

    public function down(): void
    {
        Schema::table('news_sources', function (Blueprint $table) {
            $table->dropColumn([
                'priority', 'is_active', 'source_type',
                'last_crawled_at', 'crawl_interval_minutes',
            ]);
        });
    }
};
