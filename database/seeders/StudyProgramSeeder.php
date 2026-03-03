<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $programs = [
            'Fakultas Teknik' => [
                'Teknik Sipil',
                'Teknik Mesin',
                'Teknik Elektro',
            ],
            'Fakultas Ekonomi dan Bisnis' => [
                'Akuntansi',
                'Manajemen',
                'Ekonomi Pembangunan',
            ],
            'Fakultas Ilmu Komputer' => [
                'Teknik Informatika',
                'Sistem Informasi',
                'Data Science',
            ],
            'Fakultas Hukum' => [
                'Ilmu Hukum',
            ],
            'Fakultas Kedokteran' => [
                'Pendidikan Dokter',
                'Keperawatan',
            ],
        ];

        foreach ($programs as $facultyName => $prodiList) {
            $faculty = Faculty::where('name', $facultyName)->first();
            if ($faculty) {
                foreach ($prodiList as $prodiName) {
                    StudyProgram::create([
                        'faculty_id' => $faculty->id,
                        'name'       => $prodiName,
                    ]);
                }
            }
        }
    }
}
