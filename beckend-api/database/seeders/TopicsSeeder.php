<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicsSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            ['name' => 'Politik',        'color' => '#EF4444'], // merah
            ['name' => 'Ekonomi',        'color' => '#F59E0B'], // kuning
            ['name' => 'Hukum',          'color' => '#8B5CF6'], // ungu
            ['name' => 'Pendidikan',     'color' => '#3B82F6'], // biru
            ['name' => 'Infrastruktur',  'color' => '#6B7280'], // abu
            ['name' => 'Kesehatan',      'color' => '#10B981'], // hijau
            ['name' => 'Anggaran',       'color' => '#F97316'], // oranye
            ['name' => 'Sosial',         'color' => '#EC4899'], // pink
            ['name' => 'Keamanan',       'color' => '#1F2937'], // hitam
            ['name' => 'Lingkungan',     'color' => '#065F46'], // hijau tua
        ];

        foreach ($topics as $topic) {
            Topic::firstOrCreate(
                ['name' => $topic['name']],
                [
                    'slug'  => Str::slug($topic['name']),
                    'color' => $topic['color'],
                ]
            );
        }

        $this->command->info('✅ ' . count($topics) . ' topik berhasil dibuat');
    }
}
