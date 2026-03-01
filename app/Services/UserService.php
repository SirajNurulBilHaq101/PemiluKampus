<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Ambil semua user, urut terbaru.
     */
    public function getAll(): Collection
    {
        return User::latest()->get();
    }
}
