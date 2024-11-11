<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(UserCreateRequest $request): JsonResponse
    {
        // If validation fails, it will automatically redirect or return errors
        $request->validated(); // Get validated data

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
                'message' => 'User registered successfully!'
            ],
            201
        );
    }
}
