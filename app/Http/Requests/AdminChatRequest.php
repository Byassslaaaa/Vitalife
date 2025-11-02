<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
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
                'max:2000' // Admin can send longer messages
            ],
            'conversation_id' => [
                'required',
                'integer',
                'exists:chat_conversations,id'
            ],
            'canned_response_id' => [
                'nullable',
                'integer'
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
            'message.max' => 'Pesan tidak boleh lebih dari 2000 karakter.',
            'conversation_id.required' => 'ID percakapan harus disertakan.',
            'conversation_id.exists' => 'Percakapan tidak ditemukan.'
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('message')) {
            $this->merge([
                'message' => trim($this->message)
            ]);
        }
    }
}
