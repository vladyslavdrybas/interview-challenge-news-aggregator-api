<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use function auth;
use function config;
use function md5;
use function now;

class CredentialsController extends Controller
{
    #[OA\Post(
        path: '/auth/password-reset',
        summary: 'Reset user password and issue new token',
        security: [
            [
                'bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')
            ],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['password_current', 'password'],
                        properties: [
                            new OA\Property(property: 'password_current', type: 'string', format: 'password'),
                            new OA\Property(property: 'password', type: 'string', format: 'newpassword'),
                        ],
                        type: 'object'
                    )
                )
            ]
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password reset successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password reset successfully.'),
                        new OA\Property(property: 'token', type: 'string', example: '25|GRKiThbSCUPdgA1VDqXXzC6iEk24Ue3gBvFhOQSL0a3c51e5'),
                    ]
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/422', response: 422),
        ]
    )]
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
