<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => [
                'required',
                'string',
                'min:1',
                'max:1000',
                'regex:/^(?!.*<script).*$/i' // Basic XSS prevention
            ],
            'conversation_id' => [
                'nullable',
                'integer',
                'exists:chat_conversations,id'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.min' => 'Pesan terlalu pendek.',
            'message.max' => 'Pesan tidak boleh lebih dari 1000 karakter.',
            'message.regex' => 'Pesan mengandung konten yang tidak diperbolehkan.',
            'conversation_id.exists' => 'Percakapan tidak ditemukan.'
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('message')) {
            // Trim whitespace
            $message = trim($this->message);

            // Remove excessive whitespace
            $message = preg_replace('/\s+/', ' ', $message);

            // Basic sanitization
            $message = strip_tags($message);

            $this->merge([
                'message' => $message
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Rate limiting check - prevent spam
        $userId = $this->user()->id;
        $rateLimitKey = 'chat_rate_limit_' . $userId;

        $messageCount = cache()->get($rateLimitKey, 0);

        if ($messageCount >= 10) {
            abort(429, 'Terlalu banyak pesan dalam waktu singkat. Mohon tunggu sebentar.');
        }

        cache()->put($rateLimitKey, $messageCount + 1, 60); // 10 messages per minute
    }
}
