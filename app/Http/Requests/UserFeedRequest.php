<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:25',
            'user_preferences' => 'nullable|array',
            'sort' => 'nullable|in:asc,desc',
        ];
    }
}
