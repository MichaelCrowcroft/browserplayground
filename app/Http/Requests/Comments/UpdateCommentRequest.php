<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->review);
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2500'],
        ];
    }
}