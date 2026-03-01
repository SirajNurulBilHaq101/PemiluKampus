<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    /**
     * Ambil semua event, urut terbaru, with creator.
     */
    public function getAll(): Collection
    {
        return Event::with('creator')->latest()->get();
    }

    /**
     * Buat event baru.
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Update event.
     */
    public function update(Event $event, array $data): Event
    {
        $event->update($data);
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
        return Event::with(['creator', 'candidates'])->findOrFail($id);
    }
}
