<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;

class VoteLogService
{
    /**
     * Ambil semua log vote (tanpa candidate — rahasia).
     * Hanya relasi event dan user yang di-load.
     */
    public function getAllVoteLogs(?int $eventId = null)
    {
        $query = Vote::select('id', 'event_id', 'created_at')
            ->with(['event:id,name'])
            ->latest();

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        return $query->get();
    }

    /**
     * Statistik ringkasan: total suara dan suara per event.
     */
    public function getVoteStats(): array
    {
        $totalVotes = Vote::count();

        $votesPerEvent = Event::withCount('votes')
            ->having('votes_count', '>', 0)
            ->orderByDesc('votes_count')
            ->get(['id', 'name']);

        return [
            'totalVotes'    => $totalVotes,
            'votesPerEvent' => $votesPerEvent,
        ];
    }

    /**
     * Ambil semua event untuk dropdown filter.
     */
    public function getAllEvents(): Collection
    {
        return Event::orderBy('name')->get(['id', 'name']);
    }
}
