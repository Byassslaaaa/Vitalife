<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'role_level',
        'permissions',
        'notes',
        'last_login_at',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'permissions' => 'array',
    ];

    /**
     * Scope untuk filter admin aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk filter berdasarkan role level
     */
    public function scopeByRoleLevel($query, $roleLevel)
    {
        return $query->where('role_level', $roleLevel);
    }

    /**
     * Check if admin is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role_level === 'super_admin';
    }

    /**
     * Check if admin is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if admin has permission
     */
    public function hasPermission($permission)
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check if permission exists in permissions array
        if (!$this->permissions) {
            return false;
        }

        return isset($this->permissions[$permission]) && $this->permissions[$permission] === true;
    }

    /**
     * Check if admin can manage spa
     */
    public function canManageSpa()
    {
        return $this->hasPermission('spa');
    }

    /**
     * Check if admin can manage yoga
     */
    public function canManageYoga()
    {
        return $this->hasPermission('yoga');
    }

    /**
     * Check if admin can manage gym
     */
    public function canManageGym()
    {
        return $this->hasPermission('gym');
    }

    /**
     * Check if admin can manage bookings
     */
    public function canManageBookings()
    {
        return $this->hasPermission('bookings');
    }

    /**
     * Check if admin can manage vouchers
     */
    public function canManageVouchers()
    {
        return $this->hasPermission('vouchers');
    }

    /**
     * Check if admin can manage users
     */
    public function canManageUsers()
    {
        return $this->hasPermission('users');
    }

    /**
     * Check if admin can manage admins
     */
    public function canManageAdmins()
    {
        return $this->hasPermission('admins');
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions()
    {
        return [
            'spa' => 'Kelola Spa (Spa, Services, Details)',
            'yoga' => 'Kelola Yoga (Yoga, Services, Details)',
            'gym' => 'Kelola Gym (Gym, Services, Details)',
            'bookings' => 'Kelola Bookings (Spa, Yoga, Gym Bookings)',
            'vouchers' => 'Kelola Vouchers',
            'users' => 'Kelola Users/Customers',
            'admins' => 'Kelola Admin',
            'payments' => 'Kelola Payments',
            'chat' => 'Kelola Chat',
            'feedback' => 'Kelola Feedback',
            'analytics' => 'Lihat Analytics & Reports',
        ];
    }

    /**
     * Set default permissions for new admin
     */
    public static function getDefaultPermissions()
    {
        return [
            'spa' => false,
            'yoga' => false,
            'gym' => false,
            'bookings' => false,
            'vouchers' => false,
            'users' => false,
            'admins' => false,
            'payments' => false,
            'chat' => false,
            'feedback' => false,
            'analytics' => false,
        ];
    }
}
