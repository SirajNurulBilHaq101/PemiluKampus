<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $bemEvent = Event::where('name', 'like', '%BEM%')->first();
        $bemCandidates = Candidate::where('event_id', $bemEvent->id)->get();

        // Ambil semua mahasiswa
        $mahasiswas = User::where('role', 'mahasiswa')->get();

        // Distribusi vote untuk BEM (5 dari 7 mahasiswa vote)
        $bemVoters = $mahasiswas->take(5);
        $baseTime = Carbon::parse('2026-03-01 09:00:00');

        foreach ($bemVoters as $i => $voter) {
            // Distribusi: kandidat 1 = 2 suara, kandidat 2 = 2 suara, kandidat 3 = 1 suara
            if ($i < 2) {
                $candidateId = $bemCandidates[0]->id; // Andi Wijaya
            } elseif ($i < 4) {
                $candidateId = $bemCandidates[1]->id; // Putri Rahayu
            } else {
                $candidateId = $bemCandidates[2]->id; // Fajar Nugroho
            }

            Vote::create([
                'event_id'     => $bemEvent->id,
                'candidate_id' => $candidateId,
                'user_id'      => $voter->id,
                'created_at'   => $baseTime->copy()->addMinutes($i * rand(5, 30)),
                'updated_at'   => $baseTime->copy()->addMinutes($i * rand(5, 30)),
            ]);
        }
    }
}
