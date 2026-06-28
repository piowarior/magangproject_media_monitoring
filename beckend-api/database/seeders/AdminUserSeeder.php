<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@dprd-banten.go.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@12345'),
            ]
        );
        $admin->assignRole('Admin');

        // Operator default
        $operator = User::firstOrCreate(
            ['email' => 'operator@dprd-banten.go.id'],
            [
                'name'     => 'Operator Media Monitoring',
                'password' => Hash::make('Operator@12345'),
            ]
        );
        $operator->assignRole('Operator');

        // Pimpinan default
        $pimpinan = User::firstOrCreate(
            ['email' => 'pimpinan@dprd-banten.go.id'],
            [
                'name'     => 'Ketua DPRD Banten',
                'password' => Hash::make('Pimpinan@12345'),
            ]
        );
        $pimpinan->assignRole('Pimpinan');

        $this->command->info('✅ Default users berhasil dibuat:');
        $this->command->info('   admin@dprd-banten.go.id    → Admin@12345');
        $this->command->info('   operator@dprd-banten.go.id → Operator@12345');
        $this->command->info('   pimpinan@dprd-banten.go.id → Pimpinan@12345');
    }
}
