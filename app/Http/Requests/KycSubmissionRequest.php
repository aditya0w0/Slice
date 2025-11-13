<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can submit KYC
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information - with strict validation
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\p{L}\s\'\-\.]+$/u', // Only letters, spaces, apostrophes, hyphens, dots
            ],
            'date_of_birth' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(120)->format('Y-m-d'), // Max 120 years old
                'before:' . now()->subYears(17)->format('Y-m-d'), // Min 18 years old (18th birthday must have passed)
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^[\+]?[0-9\s\-\(\)]{8,20}$/', // International phone format
                'min:8',
                'max:20',
            ],

            // Address - with sanitization
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'regex:/^[\p{L}\p{N}\s\,\.\/\-\#]+$/u', // Letters, numbers, spaces, comma, dot, slash, dash, hash
            ],
            'city' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\p{L}\s\-\.]+$/u', // Only letters, spaces, hyphens, dots
            ],
            'postal_code' => [
                'required',
                'string',
                'regex:/^[0-9]{5,10}$/', // 5-10 digit postal code
            ],

            // ID Document - strict enum validation
            'id_type' => [
                'required',
                'string',
                'in:ktp,passport,sim', // Whitelist only
            ],
            'id_number' => [
                'required',
                'string',
                'min:5',
                'max:50',
                'regex:/^[A-Z0-9\-]+$/', // Only uppercase letters, numbers, hyphens
            ],

            // File uploads - strict security validation
            'id_front' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png', // Only these image types
                'max:5120', // 5MB max
                'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000', // Reasonable dimensions
            ],
            'id_back' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
                'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            ],
            'selfie' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
                'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'full_name.regex' => 'Full name can only contain letters, spaces, apostrophes, hyphens, and dots.',
            'date_of_birth.before' => 'You must be at least 18 years old to verify your identity.',
            'date_of_birth.after' => 'Please enter a valid date of birth.',
            'phone_number.regex' => 'Please enter a valid phone number format.',
            'address.regex' => 'Address contains invalid characters.',
            'address.min' => 'Please provide a complete address (at least 10 characters).',
            'city.regex' => 'City name can only contain letters, spaces, hyphens, and dots.',
            'postal_code.regex' => 'Postal code must be 5-10 digits.',
            'id_type.in' => 'Invalid document type selected.',
            'id_number.regex' => 'Document number can only contain uppercase letters, numbers, and hyphens.',
            'id_front.dimensions' => 'ID photo must be at least 300x300 pixels.',
            'id_back.dimensions' => 'ID photo must be at least 300x300 pixels.',
            'selfie.dimensions' => 'Selfie photo must be at least 300x300 pixels.',
            'id_front.max' => 'ID photo must not exceed 5MB.',
            'id_back.max' => 'ID photo must not exceed 5MB.',
            'selfie.max' => 'Selfie photo must not exceed 5MB.',
        ];
    }

    /**
     * Prepare the data for validation (sanitization).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Sanitize string inputs
            'full_name' => $this->sanitizeString($this->input('full_name')),
            'phone_number' => $this->sanitizePhone($this->input('phone_number')),
            'address' => $this->sanitizeString($this->input('address')),
            'city' => $this->sanitizeString($this->input('city')),
            'postal_code' => preg_replace('/[^0-9]/', '', $this->input('postal_code')),
            'id_number' => strtoupper(trim($this->input('id_number'))),
        ]);
    }

    /**
     * Sanitize string input (remove dangerous characters).
     */
    private function sanitizeString(?string $value): ?string
    {
        if (!$value) return null;

        // Remove null bytes, control characters
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

        // Trim and normalize whitespace
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    /**
     * Sanitize phone number (remove formatting).
     */
    private function sanitizePhone(?string $value): ?string
    {
        if (!$value) return null;

        // Keep only digits, +, spaces, dashes, parentheses
        return preg_replace('/[^0-9\+\s\-\(\)]/', '', trim($value));
    }
}
