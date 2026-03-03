<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date'   => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function studyPrograms(): BelongsToMany
    {
        return $this->belongsToMany(StudyProgram::class, 'event_study_program');
    }

    /**
     * Cek apakah event dapat diakses oleh user berdasarkan prodi.
     * Jika event tidak memiliki scope prodi, semua user bisa akses.
     */
    public function isAccessibleBy(User $user): bool
    {
        // Jika event tidak punya scope prodi, semua bisa akses
        if ($this->studyPrograms->isEmpty()) {
            return true;
        }

        // Admin dan panitia selalu bisa akses
        if (in_array($user->role, ['admin', 'panitia'])) {
            return true;
        }

        return $this->studyPrograms->contains('id', $user->study_program_id);
    }
}
