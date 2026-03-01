<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.sign-in');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($this->authService->attempt($credentials, $request->boolean('remember'))) {
            $this->authService->regenerateSession($request);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return redirect('/');
    }
}
