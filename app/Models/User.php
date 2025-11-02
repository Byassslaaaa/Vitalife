<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'image',
        'google_id',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the conversations for this user.
     */
    public function conversations()
    {
        return $this->hasMany(ChatConversation::class, 'user_id');
    }

    /**
     * Get active chats assigned to this admin.
     */
    public function activeChats()
    {
        return $this->hasMany(ChatConversation::class, 'admin_id')
            ->where('status', 'active');
    }

    /**
     * Get all chats assigned to this admin.
     */
    public function assignedChats()
    {
        return $this->hasMany(ChatConversation::class, 'admin_id');
    }

    /**
     * Get resolved chats by this admin.
     */
    public function resolvedChats()
    {
        return $this->hasMany(ChatConversation::class, 'resolved_by');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
