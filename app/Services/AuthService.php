<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Attempt login dengan credentials.
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Regenerate session setelah login berhasil.
     */
    public function regenerateSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Logout user dan invalidate session.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
