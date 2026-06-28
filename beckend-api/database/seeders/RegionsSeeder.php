<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\GeoLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionsSeeder extends Seeder
{
    public function run(): void
    {
        // 8 Kabupaten/Kota di Provinsi Banten + koordinat GPS tengah wilayah
        $regions = [
            [
                'name' => 'Kota Serang',
                'type' => 'kota',
                'lat'  => -6.1201,
                'lng'  => 106.1501,
            ],
            [
                'name' => 'Kota Tangerang',
                'type' => 'kota',
                'lat'  => -6.1702,
                'lng'  => 106.6402,
            ],
            [
                'name' => 'Kota Tangerang Selatan',
                'type' => 'kota',
                'lat'  => -6.2888,
                'lng'  => 106.7167,
            ],
            [
                'name' => 'Kota Cilegon',
                'type' => 'kota',
                'lat'  => -6.0024,
                'lng'  => 106.0014,
            ],
            [
                'name' => 'Kabupaten Serang',
                'type' => 'kabupaten',
                'lat'  => -6.2183,
                'lng'  => 106.1553,
            ],
            [
                'name' => 'Kabupaten Tangerang',
                'type' => 'kabupaten',
                'lat'  => -6.1658,
                'lng'  => 106.5409,
            ],
            [
                'name' => 'Kabupaten Lebak',
                'type' => 'kabupaten',
                'lat'  => -6.5574,
                'lng'  => 106.2508,
            ],
            [
                'name' => 'Kabupaten Pandeglang',
                'type' => 'kabupaten',
                'lat'  => -6.3083,
                'lng'  => 106.1067,
            ],
            // Provinsi sebagai wilayah level atas
            [
                'name' => 'Provinsi Banten',
                'type' => 'provinsi',
                'lat'  => -6.4058,
                'lng'  => 106.0640,
            ],
        ];

        foreach ($regions as $data) {
            $region = Region::firstOrCreate(
                ['name' => $data['name']],
                [
                    'slug' => Str::slug($data['name']),
                    'type' => $data['type'],
                ]
            );

            // Simpan koordinat GPS
            GeoLocation::firstOrCreate(
                ['region_id' => $region->id],
                [
                    'lat' => $data['lat'],
                    'lng' => $data['lng'],
                ]
            );
        }

        $this->command->info('✅ ' . count($regions) . ' wilayah Banten + koordinat GPS berhasil dibuat');
    }
}
