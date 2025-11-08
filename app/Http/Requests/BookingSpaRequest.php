<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingSpaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Spa information
            'spa_id' => [
                'required',
                'integer',
                Rule::exists('spas', 'id_spa')
            ],

            // Service selection
            'service_id' => [
                'required',
                'integer',
                Rule::exists('spa_services', 'id')->where(function ($query) {
                    $query->where('is_active', true);
                })
            ],

            // Customer information
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\-]+$/u' // Only letters, spaces, hyphens
            ],
            'customer_email' => [
                'required',
                'email:rfc,dns',
                'max:255'
            ],
            'customer_phone' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/', // Indonesian phone format
                'min:10',
                'max:15'
            ],

            // Booking details
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'booking_time' => [
                'required',
                'date_format:H:i'
            ],

            // Optional fields
            'therapist_preference' => [
                'nullable',
                'string',
                'in:male,female,any'
            ],
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
            'spa_id.required' => 'Please select a spa location.',
            'spa_id.exists' => 'The selected spa is not available.',
            'service_id.required' => 'Please select a service.',
            'service_id.exists' => 'The selected service is not available.',
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.regex' => 'Name can only contain letters, spaces, and hyphens.',
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_phone.required' => 'Please enter your phone number.',
            'customer_phone.regex' => 'Please enter a valid phone number.',
            'customer_phone.min' => 'Phone number must be at least 10 digits.',
            'booking_date.required' => 'Please select a booking date.',
            'booking_date.after_or_equal' => 'Booking date must be today or later.',
            'booking_time.required' => 'Please select a booking time.',
            'therapist_preference.in' => 'Please select a valid therapist preference.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
            'voucher_code.exists' => 'The voucher code is invalid.',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        // Sanitize inputs
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
            // Remove extra spaces
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
