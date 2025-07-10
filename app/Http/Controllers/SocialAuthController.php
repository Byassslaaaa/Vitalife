<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider = 'google')
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider = 'google')
    {
        try {
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
            
            // Login the user
            Auth::login($user, true);
            
            // Redirect to dashboard
            return redirect()->intended('/dashboard');
            
        } catch (Exception $e) {
            \Log::error('Social callback error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
