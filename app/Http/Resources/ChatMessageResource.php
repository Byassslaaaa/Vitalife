<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
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
            'conversation_id' => $this->chat_conversation_id ?? $this->conversation_id,
            'sender_id' => $this->sender_id,
            'sender_type' => $this->sender_type,
            'message' => $this->message,
            'is_read' => $this->is_read,
            'read_at' => $this->read_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // Include sender info if loaded
            'sender' => $this->when(
                $this->sender_type === 'user' && $this->relationLoaded('user'),
                fn() => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'image' => $this->user->image,
                ]
            ),
            'admin' => $this->when(
                $this->sender_type === 'admin' && $this->relationLoaded('admin'),
                fn() => [
                    'id' => $this->admin->id,
                    'name' => $this->admin->name,
                    'image' => $this->admin->image,
                ]
            ),
        ];
    }
}
