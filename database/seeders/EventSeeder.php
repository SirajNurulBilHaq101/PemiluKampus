<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Event 1: Pemilihan BEM — semua prodi (tidak di-scope)
        Event::create([
            'name'        => 'Pemilihan Ketua BEM 2026',
            'description' => 'Pemilihan Ketua Badan Eksekutif Mahasiswa periode 2026/2027.',
            'start_date'  => '2026-03-01 08:00:00',
            'end_date'    => '2026-03-07 17:00:00',
            'status'      => 'active',
            'created_by'  => $admin->id,
        ]);
        // Tidak attach prodi = semua bisa akses

        // Event 2: Pemilihan Ketua Himpunan Informatika — scope ke prodi FIK
        $event2 = Event::create([
            'name'        => 'Pemilihan Ketua Himpunan Informatika',
            'description' => 'Pemilihan Ketua Himpunan Mahasiswa Informatika periode 2026/2027.',
            'start_date'  => '2026-03-10 08:00:00',
            'end_date'    => '2026-03-14 17:00:00',
            'status'      => 'active',
            'created_by'  => $admin->id,
        ]);

        // Scope ke semua prodi di Fakultas Ilmu Komputer
        $fikProdiIds = StudyProgram::whereHas('faculty', function ($q) {
            $q->where('name', 'Fakultas Ilmu Komputer');
        })->pluck('id')->toArray();

        $event2->studyPrograms()->sync($fikProdiIds);
    }
}
