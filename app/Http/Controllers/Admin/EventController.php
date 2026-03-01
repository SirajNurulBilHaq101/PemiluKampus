<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    public function index()
    {
        $events = $this->eventService->getAll();
        return view('admin.masterData.event.index', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'status'      => ['required', 'in:upcoming,active,completed,cancelled'],
        ]);

        $data['created_by'] = Auth::id();

        $this->eventService->create($data);

        return redirect()->route('admin.masterData.event.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $event = $this->eventService->findOrFail($id);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'status'      => ['required', 'in:upcoming,active,completed,cancelled'],
        ]);

        $this->eventService->update($event, $data);

        return redirect()->route('admin.masterData.event.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $event = $this->eventService->findOrFail($id);
        $this->eventService->delete($event);

        return redirect()->route('admin.masterData.event.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
