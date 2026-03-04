<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function index(Request $request)
    {
        $events = $this->reportService->getEventsWithVotes();

        // Tentukan event yang dipilih: dari query param atau default event pertama
        $selectedEventId = $request->query('event_id')
            ? (int) $request->query('event_id')
            : ($events->first()?->id);

        $report = null;
        if ($selectedEventId) {
            $report = $this->reportService->getEventReport($selectedEventId);
        }

        return view('admin.report.index', [
            'events'          => $events,
            'selectedEventId' => $selectedEventId,
            'report'          => $report,
        ]);
    }
}
