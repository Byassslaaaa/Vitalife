<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'category' => $this->category,

            // User info
            'user' => $this->when($this->relationLoaded('user'), fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'image' => $this->user->image,
            ]),

            // Admin info
            'admin' => $this->when($this->relationLoaded('admin') && $this->admin, fn() => [
                'id' => $this->admin->id,
                'name' => $this->admin->name,
                'image' => $this->admin->image,
            ]),

            // Timestamps
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'assigned_at' => $this->assigned_at?->toIso8601String(),
            'waiting_since' => $this->waiting_since?->toIso8601String(),
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'rated_at' => $this->rated_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // Counters
            'unread_by_admin' => $this->unread_by_admin ?? 0,
            'unread_by_user' => $this->unread_by_user ?? 0,

            // Rating
            'rating' => $this->rating,
            'rating_feedback' => $this->rating_feedback,

            // Latest message
            'latest_message' => $this->when(
                $this->relationLoaded('latestMessage') && $this->latestMessage,
                fn() => new ChatMessageResource($this->latestMessage)
            ),

            // Messages collection
            'messages' => $this->when(
                $this->relationLoaded('messages'),
                fn() => ChatMessageResource::collection($this->messages)
            ),
        ];
    }
}
