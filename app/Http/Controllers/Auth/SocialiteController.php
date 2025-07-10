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
        $socialUser = Socialite::driver($provider)->user();
        
        // Check if user already exists
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // Update only the Google ID if user already exists
            // This preserves their existing password and role
            $user->update([
                'google_id' => $socialUser->getId()
            ]);
        } else {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'user', // Default role
                'google_id' => $socialUser->getId()
            ]);
        }
        
        Auth::login($user);
        
        return redirect('/dashboard');
    }
}
