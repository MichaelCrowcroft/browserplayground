<?php

namespace App\Http\Requests\Listings;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'builder' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'url', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
        ];
    }
}
