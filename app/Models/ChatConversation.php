<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'status',
        'category',
        'last_message_at',
        'assigned_at',
        'waiting_since',
        'transferred_from',
        'transferred_at',
        'resolved_at',
        'resolved_by',
        'rating',
        'rating_feedback',
        'rated_at',
        'unread_by_admin',
        'unread_by_user',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'assigned_at' => 'datetime',
        'waiting_since' => 'datetime',
        'transferred_at' => 'datetime',
        'resolved_at' => 'datetime',
        'rated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the conversation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin assigned to this conversation.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the admin who resolved this conversation.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Get the admin who transferred this conversation.
     */
    public function transferredFromAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_from');
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Get the latest message for the conversation.
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latest();
    }

    /**
     * Get unread messages count for admin
     */
    public function unreadMessagesCount()
    {
        return $this->messages()->where('sender_type', 'user')->where('is_read', false)->count();
    }

    /**
     * Scope for active conversations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for waiting admin conversations
     */
    public function scopeWaitingAdmin($query)
    {
        return $query->where('status', 'waiting_admin');
    }

    /**
     * Scope for resolved conversations
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for assigned to specific admin
     */
    public function scopeAssignedTo($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }
}