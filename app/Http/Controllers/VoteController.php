<?php

namespace App\Http\Controllers;

use App\Services\VoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function __construct(
        private VoteService $voteService
    ) {}

    /**
     * Halaman daftar event aktif (filtered by prodi).
     */
    public function index()
    {
        $events = $this->voteService->getActiveEvents(Auth::user());
        return view('vote.index', compact('events'));
    }

    /**
     * Halaman voting — tampilkan kandidat.
     */
    public function show(int $eventId)
    {
        $event = $this->voteService->getEventWithCandidates($eventId);

        // Cek akses berdasarkan prodi
        if (!$event->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $hasVoted   = $this->voteService->hasVoted($eventId, Auth::id());
        $userVote   = $hasVoted ? $this->voteService->getUserVote($eventId, Auth::id()) : null;
        $results    = $hasVoted ? $this->voteService->getResults($eventId) : null;

        return view('vote.show', compact('event', 'hasVoted', 'userVote', 'results'));
    }

    /**
     * Submit vote.
     */
    public function store(Request $request, int $eventId)
    {
        $request->validate([
            'candidate_id' => ['required', 'exists:candidates,id'],
        ]);

        // Cek apakah event masih aktif
        $event = $this->voteService->getEventWithCandidates($eventId);
        if ($event->status !== 'active') {
            return back()->with('error', 'Event ini tidak sedang aktif.');
        }

        // Cek akses berdasarkan prodi
        if (!$event->isAccessibleBy(Auth::user())) {
            return back()->with('error', 'Anda tidak memiliki akses ke event ini.');
        }

        // Cek apakah sudah pernah vote
        if ($this->voteService->hasVoted($eventId, Auth::id())) {
            return back()->with('error', 'Anda sudah pernah memberikan suara di event ini.');
        }

        // Cek kandidat milik event ini
        $candidateIds = $event->candidates->pluck('id')->toArray();
        if (!in_array($request->candidate_id, $candidateIds)) {
            return back()->with('error', 'Kandidat tidak valid untuk event ini.');
        }

        $this->voteService->castVote($eventId, $request->candidate_id, Auth::id());

        return redirect()->route('vote.show', $eventId)
            ->with('success', 'Suara Anda berhasil dicatat! Terima kasih telah berpartisipasi.');
    }

    /**
     * Halaman detail kandidat — visi & misi lengkap.
     */
    public function candidate(int $eventId, int $candidateId)
    {
        $event     = $this->voteService->getEventWithCandidates($eventId);

        // Cek akses berdasarkan prodi
        if (!$event->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        $candidate = $event->candidates->firstWhere('id', $candidateId);

        if (!$candidate) {
            abort(404);
        }

        $hasVoted = $this->voteService->hasVoted($eventId, Auth::id());

        return view('vote.candidate', compact('event', 'candidate', 'hasVoted'));
    }
}
