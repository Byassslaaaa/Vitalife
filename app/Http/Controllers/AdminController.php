<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\AdminMiddleware;
use App\Models\spa;
use App\Models\User;
use App\Models\spesialis;
use App\Models\yoga;
use App\Models\event;
use App\Models\Payment;

class AdminController extends Controller
{
    public function Adminhomepage()
    {
        $spacount = Spa::count();
        $yogacount = Yoga::count();
        $eventcount = Event::count();
        $spescount = Spesialis::count();
        $payments = Payment::orderBy('created_at', 'desc')->take(5)->get(); // Get the 5 most recent payments
        return view('admin.dashboard', compact('spacount', 'yogacount', 'eventcount', 'spescount', 'payments'));
    }

    public function create()
    {
        return view('admin.account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.accountuser')->with('success', 'Account created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.account.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.accountuser')->with('success', 'Account updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.accountuser')->with('success', 'Account deleted successfully');
    }
}
