<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Admin;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Filter berdasarkan search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan role level
        if ($request->has('role_level') && $request->role_level !== '') {
            $query->where('role_level', $request->role_level);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $admins = $query->paginate(10)->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        $availablePermissions = Admin::getAvailablePermissions();
        return view('admin.admins.create', compact('availablePermissions'));
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_level' => 'required|in:super_admin,admin',
            'status' => 'required|in:active,inactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'nullable|array',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Nama admin wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role_level.required' => 'Level admin wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('admin_photos', 'public');
        }

        // Handle permissions
        $permissions = Admin::getDefaultPermissions();
        if ($request->has('permissions') && is_array($request->permissions)) {
            foreach ($request->permissions as $key => $value) {
                $permissions[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_level' => $validated['role_level'],
            'status' => $validated['status'],
            'permissions' => $permissions,
            'notes' => $validated['notes'] ?? null,
            'profile_photo' => $profilePhotoPath,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Display the specified admin
     */
    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit(Admin $admin)
    {
        $availablePermissions = Admin::getAvailablePermissions();
        return view('admin.admins.edit', compact('admin', 'availablePermissions'));
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role_level' => 'required|in:super_admin,admin',
            'status' => 'required|in:active,inactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'nullable|array',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'Nama admin wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role_level.required' => 'Level admin wajib dipilih',
            'status.required' => 'Status wajib dipilih',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->role_level = $validated['role_level'];
        $admin->status = $validated['status'];
        $admin->notes = $validated['notes'] ?? null;

        // Handle permissions
        $permissions = $admin->permissions ?? Admin::getDefaultPermissions();
        if ($request->has('permissions') && is_array($request->permissions)) {
            foreach ($request->permissions as $key => $value) {
                $permissions[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }
        $admin->permissions = $permissions;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($admin->profile_photo && Storage::disk('public')->exists($admin->profile_photo)) {
                Storage::disk('public')->delete($admin->profile_photo);
            }
            $admin->profile_photo = $request->file('profile_photo')->store('admin_photos', 'public');
        }

        $admin->save();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui');
    }

    /**
     * Remove the specified admin
     */
    public function destroy(Admin $admin)
    {
        // Cek apakah admin yang akan dihapus adalah diri sendiri
        if (auth()->guard('admin')->check() && auth()->guard('admin')->id() === $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Hapus foto profil jika ada
        if ($admin->profile_photo && Storage::disk('public')->exists($admin->profile_photo)) {
            Storage::disk('public')->delete($admin->profile_photo);
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    /**
     * Toggle admin status
     */
    public function toggleStatus(Admin $admin)
    {
        $admin->status = $admin->status === 'active' ? 'inactive' : 'active';
        $admin->save();

        $statusText = $admin->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.admins.index')
            ->with('success', "Admin berhasil {$statusText}");
    }
}
