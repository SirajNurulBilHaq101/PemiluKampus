<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Ambil semua event yang memiliki votes, untuk dropdown selector.
     */
    public function getEventsWithVotes(): Collection
    {
        return Event::withCount('votes')
            ->having('votes_count', '>', 0)
            ->orderByDesc('votes_count')
            ->get(['id', 'name', 'status', 'start_date', 'end_date']);
    }

    /**
     * Generate report lengkap untuk satu event.
     */
    public function getEventReport(int $eventId): array
    {
        $event = Event::with(['candidates' => function ($q) {
            $q->withCount('votes')->orderBy('candidate_number');
        }, 'studyPrograms'])
        ->withCount('votes')
        ->findOrFail($eventId);

        $totalVotes = $event->votes_count;

        // Kandidat + persentase
        $candidates = $event->candidates->map(function ($candidate) use ($totalVotes) {
            $candidate->percentage = $totalVotes > 0
                ? round(($candidate->votes_count / $totalVotes) * 100, 1)
                : 0;
            return $candidate;
        });

        // Pemenang
        $winner = $candidates->sortByDesc('votes_count')->first();

        // Total mahasiswa yang eligible untuk event ini
        $eligibleQuery = User::where('role', 'mahasiswa');
        if ($event->studyPrograms->isNotEmpty()) {
            $eligibleQuery->whereIn('study_program_id', $event->studyPrograms->pluck('id'));
        }
        $totalEligible = $eligibleQuery->count();

        // Persentase partisipasi
        $participationRate = $totalEligible > 0
            ? round(($totalVotes / $totalEligible) * 100, 1)
            : 0;

        // Partisipasi per fakultas
        $facultyStats = $this->getFacultyParticipation($eventId, $event->studyPrograms);

        return [
            'event'             => $event,
            'candidates'        => $candidates,
            'winner'            => $winner,
            'totalVotes'        => $totalVotes,
            'totalEligible'     => $totalEligible,
            'participationRate' => $participationRate,
            'facultyStats'      => $facultyStats,
        ];
    }

    /**
     * Hitung partisipasi per fakultas dan prodi.
     */
    private function getFacultyParticipation(int $eventId, $scopedPrograms): array
    {
        $faculties = Faculty::with(['studyPrograms' => function ($q) {
            $q->withCount(['events']); // just load them
        }])->get();

        $result = [];

        foreach ($faculties as $faculty) {
            $facultyData = [
                'name'       => $faculty->name,
                'programs'   => [],
                'totalUsers' => 0,
                'totalVoted' => 0,
            ];

            foreach ($faculty->studyPrograms as $prodi) {
                // Jika event punya scope prodi, skip prodi yang tidak termasuk
                if ($scopedPrograms->isNotEmpty() && !$scopedPrograms->contains('id', $prodi->id)) {
                    continue;
                }

                $totalUsers = User::where('role', 'mahasiswa')
                    ->where('study_program_id', $prodi->id)
                    ->count();

                $totalVoted = Vote::where('event_id', $eventId)
                    ->whereHas('user', function ($q) use ($prodi) {
                        $q->where('study_program_id', $prodi->id);
                    })
                    ->count();

                if ($totalUsers === 0) {
                    continue;
                }

                $percentage = $totalUsers > 0
                    ? round(($totalVoted / $totalUsers) * 100, 1)
                    : 0;

                $facultyData['programs'][] = [
                    'name'       => $prodi->name,
                    'totalUsers' => $totalUsers,
                    'totalVoted' => $totalVoted,
                    'percentage' => $percentage,
                ];

                $facultyData['totalUsers'] += $totalUsers;
                $facultyData['totalVoted'] += $totalVoted;
            }

            if (!empty($facultyData['programs'])) {
                $facultyData['percentage'] = $facultyData['totalUsers'] > 0
                    ? round(($facultyData['totalVoted'] / $facultyData['totalUsers']) * 100, 1)
                    : 0;
                $result[] = $facultyData;
            }
        }

        return $result;
    }
}
