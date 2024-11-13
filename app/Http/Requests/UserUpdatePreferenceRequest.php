<?php

namespace App\Http\Requests;

use App\Models\UserPreference;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserUpdatePreferenceRequest',
    required: ['title'],
    properties: [
        new OA\Property(property: 'title', description: 'The title of the preference', type: 'string', example: 'User Preferences'),
        new OA\Property(property: 'slug', description: 'The slug for the preference', type: 'string', example: 'user-preferences-slug'),
        new OA\Property(property: 'categories', ref: '#/components/schemas/IntegerArray'),
        new OA\Property(property: 'sources', ref: '#/components/schemas/IntegerArray'),
        new OA\Property(property: 'authors', ref: '#/components/schemas/IntegerArray'),
    ],
    type: 'object'
)]
class UserUpdatePreferenceRequest extends FormRequest
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
        $rules = UserPreference::CREATE_RULES;
        if (!empty($rules['slug'])) {
            $id = (int) $this->id;
            $rules['slug'] = 'string|max:255|unique:user_preferences,slug,' . $id;
        }

        return $rules;
    }
}
