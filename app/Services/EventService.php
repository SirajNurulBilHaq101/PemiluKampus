<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    /**
     * Ambil semua event, urut terbaru, with creator & studyPrograms.
     */
    public function getAll(): Collection
    {
        return Event::with(['creator', 'studyPrograms.faculty'])->latest()->get();
    }

    /**
     * Buat event baru dan sync prodi.
     */
    public function create(array $data, array $studyProgramIds = []): Event
    {
        $event = Event::create($data);

        if (!empty($studyProgramIds)) {
            $event->studyPrograms()->sync($studyProgramIds);
        }

        return $event;
    }

    /**
     * Update event dan sync prodi.
     */
    public function update(Event $event, array $data, array $studyProgramIds = []): Event
    {
        $event->update($data);
        $event->studyPrograms()->sync($studyProgramIds);

        return $event;
    }

    /**
     * Hapus event.
     */
    public function delete(Event $event): void
    {
        $event->delete();
    }

    /**
     * Cari event berdasarkan ID.
     */
    public function findOrFail(int $id): Event
    {
        return Event::with(['creator', 'candidates', 'studyPrograms'])->findOrFail($id);
    }

    /**
     * Ambil semua fakultas beserta prodi-nya (untuk form select).
     */
    public function getFacultiesWithPrograms(): Collection
    {
        return Faculty::with('studyPrograms')->orderBy('name')->get();
    }
}
