<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;

class VoteService
{
    /**
     * Ambil semua event yang sedang aktif.
     */
    public function getActiveEvents(): Collection
    {
        return Event::where('status', 'active')
            ->withCount('candidates')
            ->latest()
            ->get();
    }

    /**
     * Ambil event beserta kandidatnya.
     */
    public function getEventWithCandidates(int $eventId): Event
    {
        return Event::with(['candidates' => function ($query) {
            $query->orderBy('candidate_number');
        }])
        ->withCount('votes')
        ->findOrFail($eventId);
    }

    /**
     * Cek apakah user sudah vote di event tertentu.
     */
    public function hasVoted(int $eventId, int $userId): bool
    {
        return Vote::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Ambil vote user di event tertentu.
     */
    public function getUserVote(int $eventId, int $userId): ?Vote
    {
        return Vote::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Submit vote.
     */
    public function castVote(int $eventId, int $candidateId, int $userId): Vote
    {
        return Vote::create([
            'event_id'     => $eventId,
            'candidate_id' => $candidateId,
            'user_id'      => $userId,
        ]);
    }

    /**
     * Ambil hasil vote per kandidat di suatu event.
     */
    public function getResults(int $eventId): Collection
    {
        return Candidate::where('event_id', $eventId)
            ->withCount('votes')
            ->orderBy('candidate_number')
            ->get();
    }
}
