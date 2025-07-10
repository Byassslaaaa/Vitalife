<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:8|confirmed',
    //         'profile_picture' => 'nullable|image|max:1024',
    //     ]);

    //     $user->first_name = $request->first_name;
    //     $user->last_name = $request->last_name;
    //     $user->email = $request->email;

    //     if ($request->filled('password')) {
    //         $user->password = Hash::make($request->password);
    //     }

    //     if ($request->hasFile('profile_picture')) {
    //         $path = $request->file('profile_picture')->store('admin_profile_pictures', 'public');
    //         $user->profile_picture = $path;
    //     }

    //     $user->save();

    //     return redirect()->route('admin.profile.edit')->with('status', 'Admin profile updated successfully.');
    // }
}
