<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $listing = $this->route('listing');
        return $listing->creator_id === auth()->id() || auth()->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:1',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Listing title is required.',
            'description.required' => 'Please provide a description for your listing.',
            'price_per_day.required' => 'Price per day is required.',
            'price_per_day.numeric' => 'Price must be a valid number.',
            'price_per_day.min' => 'Price must be at least $1.',
            'location.required' => 'Location is required.',
            'image.image' => 'Please upload a valid image file.',
            'image.mimes' => 'Image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}