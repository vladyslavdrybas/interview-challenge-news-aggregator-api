<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class RegisterController extends Controller
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        ref: '#/components/schemas/UserCreateRequest'
                    )
                )
            ]
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User successfully registered',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User registered successfully!'),
                    ]
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/422', response: 422),
        ]
    )]
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
