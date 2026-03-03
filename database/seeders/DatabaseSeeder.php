<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FacultySeeder::class,
            StudyProgramSeeder::class,
            UserSeeder::class,
            EventSeeder::class,
            CandidateSeeder::class,
            VoteSeeder::class,
        ]);
    }
}
