<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // URUTAN PENTING — jangan diubah!
        // 1. Roles & Permissions dulu sebelum user dibuat
        $this->call([
            RolesAndPermissionsSeeder::class, // harus pertama
            AdminUserSeeder::class,           // butuh roles sudah ada
            TopicsSeeder::class,              // master data topik
            RegionsSeeder::class,             // master data wilayah + GPS
            NewsSourcesSeeder::class,         // sumber berita RSS
        ]);
    }
}
