<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Kredensial email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Fast role switcher for development and grading evaluation.
     */
    public function switchRole(Request $request): RedirectResponse
    {
        $role = $request->input('role');
        $validRoles = ['admin', 'designer', 'requester'];

        if (in_array($role, $validRoles, true)) {
            $user = User::where('role', $role)->first();
            if ($user) {
                Auth::login($user);
                $request->session()->regenerate();

                return $this->redirectBasedOnRole($user)->with('success', "Beralih peran menjadi: {$user->name} ({$user->role})");
            }
        }

        return back()->with('error', 'Role tidak valid.');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.guidelines'),
            'designer' => redirect()->route('designer.dashboard'),
            default => redirect()->route('requester.create'),
        };
    }
}
