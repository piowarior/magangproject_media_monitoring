<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_sources', function (Blueprint $table) {
            // Hapus kolom RSS-centric yang tidak relevan lagi
            $table->dropColumn(['url', 'type', 'source_type', 'crawl_interval_minutes']);

            // Tambah kolom untuk media registry
            $table->string('domain')->nullable()->after('name');         // contoh: kompas.com
            $table->decimal('reliability_score', 4, 2)->default(0.00)   // skor kepercayaan media
                  ->after('domain');
            $table->unsignedInteger('total_news')->default(0)            // cached counter
                  ->after('reliability_score');
            $table->unsignedInteger('positive_count')->default(0)->after('total_news');
            $table->unsignedInteger('neutral_count')->default(0)->after('positive_count');
            $table->unsignedInteger('negative_count')->default(0)->after('neutral_count');
            $table->text('notes')->nullable()->after('negative_count'); // catatan admin
            // is_active sudah ada (untuk blacklist media)
            // priority sudah ada (untuk tampilan ranking)
            // last_crawled_at sudah ada (terakhir ditemukan)
        });
    }

    public function down(): void
    {
        Schema::table('news_sources', function (Blueprint $table) {
            $table->dropColumn([
                'domain', 'reliability_score', 'total_news',
                'positive_count', 'neutral_count', 'negative_count', 'notes',
            ]);
            $table->string('url')->nullable();
            $table->string('type')->nullable();
            $table->string('source_type')->default('rss');
            $table->integer('crawl_interval_minutes')->default(60);
        });
    }
};
