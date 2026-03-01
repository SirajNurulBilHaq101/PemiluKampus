<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Akun Mahasiswa
        User::factory()->create([
            'name'     => 'Mahasiswa User',
            'email'    => 'mhs@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Mahasiswa User 2',
            'email'    => 'mhs2@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Ahmad Rifai',
            'email'    => 'ahmad@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Siti Nurhaliza',
            'email'    => 'siti@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Dewi Lestari',
            'email'    => 'dewi@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'     => 'Rizky Pratama',
            'email'    => 'rizky@mhs.com',
            'role'     => 'mahasiswa',
            'password' => Hash::make('mhs'),
        ]);

        // Akun Panitia
        User::factory()->create([
            'name'     => 'Panitia User',
            'email'    => 'panitia@panitia.com',
            'role'     => 'panitia',
            'password' => Hash::make('panitia'),
        ]);

        // Akun Admin
        User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@admin.com',
            'role'     => 'admin',
            'password' => Hash::make('admin'),
        ]);
    }
}
