<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use function response;

class LoginController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $request->validated();

        // Check if the user exists and if the password is correct
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401); // Return 401 Unauthorized
        }

        // Generate the token using Sanctum
        $token = $user->createToken(md5(config('app.key')))->plainTextToken;

        // Return the token as a response
        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }
}
