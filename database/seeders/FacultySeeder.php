<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faculties = [
            'Fakultas Teknik',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Ilmu Komputer',
            'Fakultas Hukum',
            'Fakultas Kedokteran',
        ];

        foreach ($faculties as $name) {
            Faculty::create(['name' => $name]);
        }
    }
}
