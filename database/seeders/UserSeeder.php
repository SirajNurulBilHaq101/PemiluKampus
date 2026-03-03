<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Ambil beberapa prodi
        $teknikInformatika = StudyProgram::where('name', 'Teknik Informatika')->first();
        $sistemInformasi   = StudyProgram::where('name', 'Sistem Informasi')->first();
        $manajemen         = StudyProgram::where('name', 'Manajemen')->first();
        $teknikSipil       = StudyProgram::where('name', 'Teknik Sipil')->first();
        $akuntansi         = StudyProgram::where('name', 'Akuntansi')->first();

        // Akun Mahasiswa — Fakultas Ilmu Komputer
        User::factory()->create([
            'name'             => 'Mahasiswa User',
            'email'            => 'mhs@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $teknikInformatika?->id,
            'password'         => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'             => 'Mahasiswa User 2',
            'email'            => 'mhs2@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $sistemInformasi?->id,
            'password'         => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'             => 'Ahmad Rifai',
            'email'            => 'ahmad@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $teknikInformatika?->id,
            'password'         => Hash::make('mhs'),
        ]);

        // Akun Mahasiswa — Fakultas Ekonomi dan Bisnis
        User::factory()->create([
            'name'             => 'Siti Nurhaliza',
            'email'            => 'siti@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $manajemen?->id,
            'password'         => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'             => 'Budi Santoso',
            'email'            => 'budi@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $akuntansi?->id,
            'password'         => Hash::make('mhs'),
        ]);

        // Akun Mahasiswa — Fakultas Teknik
        User::factory()->create([
            'name'             => 'Dewi Lestari',
            'email'            => 'dewi@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $teknikSipil?->id,
            'password'         => Hash::make('mhs'),
        ]);

        User::factory()->create([
            'name'             => 'Rizky Pratama',
            'email'            => 'rizky@mhs.com',
            'role'             => 'mahasiswa',
            'study_program_id' => $teknikInformatika?->id,
            'password'         => Hash::make('mhs'),
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
