<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Check user role and redirect accordingly
            \Log::info('User authenticated', [
                'user_id' => Auth::id(),
                'role' => Auth::user()->role ?? 'unknown',
                'email' => Auth::user()->email
            ]);
            $user = Auth::user();
            $redirectRoute = $user && $user->role === 'admin' 
                ? route('admin.dashboard', absolute: false) 
                : route('dashboard', absolute: false);

            if ($request->wantsJson()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Logged in successfully!',
                    'redirect' => $redirectRoute
                ]);
            }

            return redirect()->intended($redirectRoute)->with('status', 'Logged in successfully!');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'The provided credentials do not match our records.'
                ], 422);
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('status', 'Logged out successfully!');
    }
}