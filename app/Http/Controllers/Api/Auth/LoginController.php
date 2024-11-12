<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use function response;

class LoginController extends Controller
{
    #[OA\Post(
        path: '/auth/login',
        summary: 'Login user and issue token',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        ref: '#/components/schemas/LoginRequest'
                    )
                )
            ]
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Login successful'),
                        new OA\Property(property: 'token', type: 'string', example: '8|fV0i6wBom5uU7Kb9DJyeEWoWGio7poo0IKwYP0bYb734cfdc')
                    ]
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/422', response: 422),
        ]
    )]
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
