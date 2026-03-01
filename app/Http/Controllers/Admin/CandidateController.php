<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CandidateService;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function __construct(
        private CandidateService $candidateService,
        private EventService $eventService
    ) {}

    public function index()
    {
        $candidates = $this->candidateService->getAll();
        $events = $this->eventService->getAll();
        return view('admin.masterData.candidate.index', compact('candidates', 'events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id'         => ['required', 'exists:events,id'],
            'name'             => ['required', 'string', 'max:255'],
            'photo'            => ['nullable', 'image', 'max:2048'],
            'vision'           => ['nullable', 'string'],
            'mission'          => ['nullable', 'string'],
            'candidate_number' => [
                'required', 'integer', 'min:1',
                Rule::unique('candidates')->where('event_id', $request->event_id),
            ],
        ]);

        $this->candidateService->create($data);

        return redirect()->route('admin.masterData.candidate.index')
            ->with('success', 'Kandidat berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $candidate = $this->candidateService->findOrFail($id);

        $data = $request->validate([
            'event_id'         => ['required', 'exists:events,id'],
            'name'             => ['required', 'string', 'max:255'],
            'photo'            => ['nullable', 'image', 'max:2048'],
            'vision'           => ['nullable', 'string'],
            'mission'          => ['nullable', 'string'],
            'candidate_number' => [
                'required', 'integer', 'min:1',
                Rule::unique('candidates')->where('event_id', $request->event_id)->ignore($candidate->id),
            ],
        ]);

        $this->candidateService->update($candidate, $data);

        return redirect()->route('admin.masterData.candidate.index')
            ->with('success', 'Kandidat berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $candidate = $this->candidateService->findOrFail($id);
        $this->candidateService->delete($candidate);

        return redirect()->route('admin.masterData.candidate.index')
            ->with('success', 'Kandidat berhasil dihapus.');
    }
}
