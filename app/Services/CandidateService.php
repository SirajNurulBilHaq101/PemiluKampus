<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class CandidateService
{
    /**
     * Ambil semua kandidat, urut terbaru, with event.
     */
    public function getAll(): Collection
    {
        return Candidate::with('event')->latest()->get();
    }

    /**
     * Buat kandidat baru.
     */
    public function create(array $data): Candidate
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $data['photo'] = $data['photo']->store('candidates', 'public');
        }

        return Candidate::create($data);
    }

    /**
     * Update kandidat.
     */
    public function update(Candidate $candidate, array $data): Candidate
    {
        if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
            // Hapus foto lama jika ada
            if ($candidate->photo && file_exists(storage_path('app/public/' . $candidate->photo))) {
                unlink(storage_path('app/public/' . $candidate->photo));
            }
            $data['photo'] = $data['photo']->store('candidates', 'public');
        }

        $candidate->update($data);
        return $candidate;
    }

    /**
     * Hapus kandidat.
     */
    public function delete(Candidate $candidate): void
    {
        if ($candidate->photo && file_exists(storage_path('app/public/' . $candidate->photo))) {
            unlink(storage_path('app/public/' . $candidate->photo));
        }
        $candidate->delete();
    }

    /**
     * Cari kandidat berdasarkan ID.
     */
    public function findOrFail(int $id): Candidate
    {
        return Candidate::with('event')->findOrFail($id);
    }
}
