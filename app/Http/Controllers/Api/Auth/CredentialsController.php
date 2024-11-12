<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function auth;
use function config;
use function md5;
use function now;

class CredentialsController extends Controller
{
    public function store(PasswordResetRequest $request)
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth('sanctum')->user();

        $user->password = Hash::make($validated['password']);
        $user->save();

        $user->tokens()->delete();

        $token = $user->createToken(md5(config('app.key')), ['*'], now()->addWeek())->plainTextToken;

        return response()->json(
            [
                'message' => 'Password reset successfully.',
                'token' => $token,
            ],
            200
        );
    }
}
