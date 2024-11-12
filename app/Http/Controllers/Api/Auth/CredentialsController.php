<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;
use function auth;
use function config;
use function event;
use function md5;
use function now;
use function response;

class CredentialsController extends Controller
{
    #[OA\Post(
        path: '/auth/password-update',
        summary: 'Update password and issue new token',
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
                        ref: '#/components/schemas/PasswordUpdateRequest'
                    )
                )
            ]
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password update successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password update successfully.'),
                        new OA\Property(property: 'token', type: 'string', example: '25|GRKiThbSCUPdgA1VDqXXzC6iEk24Ue3gBvFhOQSL0a3c51e5'),
                    ]
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/422', response: 422),
        ]
    )]
    public function passwordUpdate(PasswordUpdateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth('sanctum')->user();

        $user->password = Hash::make($validated['password']);
        $user->save();

        $user->tokens()->delete();

        event(new PasswordReset($user));

        $token = $user->createToken(md5(config('app.key')), ['*'], now()->addWeek())->plainTextToken;

        return response()->json(
            [
                'message' => 'Password update successfully.',
                'token' => $token,
            ],
            200
        );
    }

    #[OA\Post(
        path: '/auth/password-forgot',
        summary: 'Request password reset link',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        ref: '#/components/schemas/ForgotPasswordRequest'
                    )
                )
            ]
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Reset link sent successfully.'),
            new OA\Response(ref: '#/components/responses/404', response: 404),
            new OA\Response(ref: '#/components/responses/422', response: 422),
        ]
    )]
    public function passwordForgot(ForgotPasswordRequest $request): JsonResponse
    {
        $request->validated();

        // Send password reset link
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(
                [
                    'message' => 'Reset link sent successfully.',
                ]
            );
        }

        return response()->json(
            [
                'message' => 'Not found.'
            ],
            404
        );
    }
}
