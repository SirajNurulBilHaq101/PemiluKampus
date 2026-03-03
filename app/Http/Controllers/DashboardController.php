<?php

namespace App\Http\Controllers;

use App\Services\VoteService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private VoteService $voteService
    ) {}

    public function index()
    {
        $activeEvents = $this->voteService->getActiveEvents(Auth::user());

        // Untuk setiap event aktif, ambil hasil vote per kandidat
        $quickCounts = [];
        foreach ($activeEvents as $event) {
            $results = $this->voteService->getResults($event->id);
            $totalVotes = $results->sum('votes_count');
            $quickCounts[$event->id] = [
                'event'      => $event,
                'results'    => $results,
                'totalVotes' => $totalVotes,
                'hasVoted'   => $this->voteService->hasVoted($event->id, Auth::id()),
            ];
        }

        return view('dashboard', compact('activeEvents', 'quickCounts'));
    }
}
