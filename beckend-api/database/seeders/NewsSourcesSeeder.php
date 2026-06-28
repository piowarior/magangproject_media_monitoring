<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourcesSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            // Google News RSS (primary source, via search query)
            [
                'name' => 'Google News',
                'url'  => 'https://news.google.com/rss/search?q={keyword}&hl=id&gl=ID&ceid=ID:id',
                'type' => 'RSS',
            ],
            // Media nasional yang sering meliput DPRD Banten
            [
                'name' => 'Kompas',
                'url'  => 'https://rss.kompas.com/rss/hl-latest',
                'type' => 'RSS',
            ],
            [
                'name' => 'Tribun Banten',
                'url'  => 'https://banten.tribunnews.com/rss',
                'type' => 'RSS',
            ],
            [
                'name' => 'Radar Banten',
                'url'  => 'https://radarbanten.co.id/feed',
                'type' => 'RSS',
            ],
            [
                'name' => 'Detik News',
                'url'  => 'https://rss.detik.com/index.php/detikcom',
                'type' => 'RSS',
            ],
            [
                'name' => 'Antara News',
                'url'  => 'https://www.antaranews.com/rss/terkini.xml',
                'type' => 'RSS',
            ],
            [
                'name' => 'Bantenhits',
                'url'  => 'https://bantenhits.com/feed',
                'type' => 'RSS',
            ],
            [
                'name' => 'Kabar Banten',
                'url'  => 'https://kabarbanten.pikiran-rakyat.com/rss',
                'type' => 'RSS',
            ],
        ];

        foreach ($sources as $source) {
            NewsSource::firstOrCreate(
                ['name' => $source['name']],
                [
                    'url'  => $source['url'],
                    'type' => $source['type'],
                ]
            );
        }

        $this->command->info('✅ ' . count($sources) . ' sumber berita berhasil dibuat');
    }
}
