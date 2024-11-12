<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use OpenApi\Attributes as OA;
use function auth;

#[OA\Schema(
    schema: 'PasswordUpdateRequest',
    required: ['password_current', 'password'],
    properties: [
        new OA\Property(property: 'password_current', type: 'string', format: 'password', example: 'password'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'newpassword'),
    ],
    type: 'object'
)]
class PasswordUpdateRequest extends FormRequest
{
    /**
     * ATTENTION!
     * Password Reset method is a crucial operation which can lead to account lose
     * I cannot 100% rely on token, as it can be intercepted
     * That's why I must compare it with existed password
     * Additionally, I will need existed password to force owner does not repeat himself
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $tokenUser = auth('sanctum')->user();
        $passwordCurrent = $this->get('password_current');

        return Hash::check($passwordCurrent, $tokenUser->getAuthPassword())
        ;
    }

    /**
     * I want to be sure that owner will change his password to prevent future leaks
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password_current' => ['required', 'string', 'min:6', 'different:password'],
            'password' => ['required', 'string', 'min:6', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'password_current.different' => 'Please, do not repeat yourself. It will make your account more secure.',
        ];
    }
}
