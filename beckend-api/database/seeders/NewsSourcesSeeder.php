<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

/**
 * Seed media yang diperkirakan akan sering muncul saat crawling.
 * PENTING: Tabel news_sources adalah registry media yang ditemukan oleh crawler,
 * BUKAN daftar RSS yang harus di-crawl satu-satu.
 *
 * Crawler menggunakan SATU Google News RSS dengan keyword search:
 * https://news.google.com/rss/search?q={keyword}&hl=id&gl=ID&ceid=ID:id
 *
 * Dari hasil Google News, crawler mengekstrak nama/domain media
 * lalu mencari atau membuat entri di tabel ini via NewsSource::findOrCreateByName()
 */
class NewsSourcesSeeder extends Seeder
{
    public function run(): void
    {
        // Seed data awal media yang kemungkinan besar akan ditemukan
        // saat crawling berita DPRD Banten
        $sources = [
            ['name' => 'Kompas',          'domain' => 'kompas.com'],
            ['name' => 'Detik News',      'domain' => 'detik.com'],
            ['name' => 'Antara News',     'domain' => 'antaranews.com'],
            ['name' => 'Tempo',           'domain' => 'tempo.co'],
            ['name' => 'CNN Indonesia',   'domain' => 'cnnindonesia.com'],
            ['name' => 'Tribun Banten',   'domain' => 'banten.tribunnews.com'],
            ['name' => 'Radar Banten',    'domain' => 'radarbanten.co.id'],
            ['name' => 'Kabar Banten',    'domain' => 'kabarbanten.pikiran-rakyat.com'],
            ['name' => 'Bantenhits',      'domain' => 'bantenhits.com'],
            ['name' => 'Liputan6',        'domain' => 'liputan6.com'],
            ['name' => 'Republika',       'domain' => 'republika.co.id'],
            ['name' => 'JPNN',            'domain' => 'jpnn.com'],
        ];

        foreach ($sources as $source) {
            NewsSource::firstOrCreate(
                ['name' => $source['name']],
                [
                    'domain'    => $source['domain'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ ' . count($sources) . ' media pre-registered ke news_sources');
        $this->command->info('   (Data real akan diisi otomatis saat crawler berjalan)');
    }
}
