<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VoteLogService;
use Illuminate\Http\Request;

class VoteLogController extends Controller
{
    protected VoteLogService $voteLogService;

    public function __construct(VoteLogService $voteLogService)
    {
        $this->voteLogService = $voteLogService;
    }

    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        $logs    = $this->voteLogService->getAllVoteLogs($eventId ? (int) $eventId : null);
        $stats   = $this->voteLogService->getVoteStats();
        $events  = $this->voteLogService->getAllEvents();

        return view('admin.voteLog.index', [
            'logs'          => $logs,
            'events'        => $events,
            'selectedEvent' => $eventId,
            'totalVotes'    => $stats['totalVotes'],
            'votesPerEvent' => $stats['votesPerEvent'],
        ]);
    }
}
