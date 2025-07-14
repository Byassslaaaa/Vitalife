<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Cek email
        $email = $socialUser->getEmail();
        $providerId = $socialUser->getId();

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update ID berdasarkan provider
            if ($provider === 'google') {
                $user->update(['google_id' => $providerId]);
            } elseif ($provider === 'facebook') {
                $user->update(['facebook_id' => $providerId]);
            }
        } else {
            // Buat user baru
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $email,
                'password' => bcrypt(Str::random(16)),
                'role' => 'user',
                'google_id' => $provider === 'google' ? $providerId : null,
                'facebook_id' => $provider === 'facebook' ? $providerId : null,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
