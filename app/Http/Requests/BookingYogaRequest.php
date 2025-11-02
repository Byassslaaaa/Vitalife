<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingYogaRequest extends FormRequest
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
            // Yoga information
            'yoga_id' => [
                'required',
                'integer',
                Rule::exists('yogas', 'id_yoga')
            ],

            // Service selection
            'service_id' => [
                'required',
                'integer',
                Rule::exists('yoga_services', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                })
            ],

            // Customer information
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\-]+$/u'
            ],
            'customer_email' => [
                'required',
                'email:rfc,dns',
                'max:255'
            ],
            'customer_phone' => [
                'required',
                'string',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:20'
            ],

            // Booking details
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'booking_time' => [
                'nullable',
                'date_format:H:i'
            ],
            'class_type' => [
                'required',
                'string',
                'max:100'
            ],
            'customer_address' => [
                'nullable',
                'string',
                'max:500'
            ],

            // Optional fields
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],

            // Voucher (optional)
            'voucher_code' => [
                'nullable',
                'string',
                'exists:vouchers,code'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'yoga_id.required' => 'Please select a yoga studio.',
            'yoga_id.exists' => 'The selected yoga studio is not available.',
            'service_id.required' => 'Please select a yoga class.',
            'service_id.exists' => 'The selected yoga class is not available.',
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.regex' => 'Name can only contain letters, spaces, and hyphens.',
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_phone.required' => 'Please enter your phone number.',
            'customer_phone.regex' => 'Please enter a valid phone number.',
            'customer_phone.min' => 'Phone number must be at least 10 digits.',
            'booking_date.required' => 'Please select a booking date.',
            'booking_date.after_or_equal' => 'Booking date must be today or later.',
            'class_type.required' => 'Please select a class type.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
            'voucher_code.exists' => 'The voucher code is invalid.',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('customer_name')) {
            $this->merge([
                'customer_name' => trim($this->customer_name)
            ]);
        }

        if ($this->has('customer_email')) {
            $this->merge([
                'customer_email' => strtolower(trim($this->customer_email))
            ]);
        }

        if ($this->has('customer_phone')) {
            $phone = preg_replace('/\s+/', '', $this->customer_phone);
            $this->merge([
                'customer_phone' => $phone
            ]);
        }

        if ($this->has('voucher_code')) {
            $this->merge([
                'voucher_code' => strtoupper(trim($this->voucher_code))
            ]);
        }
    }
}
