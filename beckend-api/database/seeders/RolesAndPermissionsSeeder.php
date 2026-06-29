<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Definisi Permissions ───────────────────────────────────────────────

        $permissions = [
            // User management
            'manage users',

            // Keyword management
            'manage keywords',
            'view keywords',
            'trigger crawl',

            // News
            'view news',

            // Reports
            'view reports',
            'create reports',
            'export reports',

            // Crawler
            'view crawl logs',

            // Dashboard
            'view dashboard',
            'view map',

            // Settings
            'view audit logs',
            'manage news sources',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ─── Buat Role & Assign Permission ─────────────────────────────────────

        // ADMIN — akses penuh
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        // OPERATOR — monitoring + laporan (tidak bisa manage user)
        $operator = Role::firstOrCreate(['name' => 'Operator', 'guard_name' => 'web']);
        $operator->syncPermissions([
            'view keywords',
            'manage keywords',
            'trigger crawl',
            'view news',
            'view reports',
            'create reports',
            'export reports',
            'view crawl logs',
            'view dashboard',
            'view map',
        ]);

        // PIMPINAN — read-only (hanya lihat dashboard & laporan)
        $pimpinan = Role::firstOrCreate(['name' => 'Pimpinan', 'guard_name' => 'web']);
        $pimpinan->syncPermissions([
            'view dashboard',
            'view map',
            'view reports',
            'view news',
        ]);

        $this->command->info('✅ Roles & Permissions berhasil dibuat:');
        $this->command->info('   Admin    → ' . $admin->permissions->count() . ' permissions');
        $this->command->info('   Operator → ' . $operator->permissions->count() . ' permissions');
        $this->command->info('   Pimpinan → ' . $pimpinan->permissions->count() . ' permissions');
    }
}
