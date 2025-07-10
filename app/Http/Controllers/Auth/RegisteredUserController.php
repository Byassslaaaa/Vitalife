<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\NewUserRegistered;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'user', // Set default role to 'user'
                'password' => Hash::make($request->password),
                'provider' => $request->provider
            ]);

            event(new Registered($user));

            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewUserRegistered($user));
            }

\Log::info('Email user baru: ' . $user->email);
// Kirim email selamat datang ke pengguna yang baru mendaftar
try {
    Mail::to($user->email)->send(new WelcomeEmail($user));
} catch (\Exception $e) {
    \Log::error('Gagal mengirim email: ' . $e->getMessage());
}

            Auth::login($user);

            // Since all users are regular users, we can simplify this logic
            $redirectRoute = route('dashboard');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful!',
                    'redirect' => $redirectRoute
                ]);
            }

            return redirect()->route('dashboard')->with('security_notice', 'Jika Anda melihat peringatan keamanan dari Google, itu adalah fitur normal. Kami sarankan Anda mengikuti saran untuk meningkatkan keamanan akun Anda.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}
