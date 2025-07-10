<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function create()
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'current_email' => ['required', 'email'],
            'new_email' => ['required', 'email', 'confirmed', Rule::unique('users', 'email')->ignore($request->user()->id)],
        ]);

        $user = $request->user();

        if ($request->current_email !== $user->email) {
            return back()->withErrors(['current_email' => 'The provided email does not match your current email.']);
        }

        $user->email = $request->new_email;
        $user->email_verified_at = null;
        $user->save();

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'email-updated');
    }

    public function updateImage(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('admin/images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }
    
        try {
            $user = $request->user();
            $user->fill($validatedData);
            $user->save();
            return redirect()->route('admin.spaShow', $user)->with('success', 'Data Spesialis berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data Spesialis. Silakan coba lagi.');
        }
    }
}
