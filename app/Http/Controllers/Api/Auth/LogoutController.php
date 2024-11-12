<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function response;

class LogoutController extends Controller
{
    #[OA\Get(
        path: '/auth/logout',
        summary: 'Logout user and invalidate token',
        security: [
            [
                'bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')
            ],
        ],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out.'),
                    ]
                )
            ),
            new OA\Response(response: 200, description: 'Successfully logged out'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function destroy(Request $request): JsonResponse
    {
        # remove all access tokens for the user
        # we can split this on two methods "logout from all devices" and "logout from the current"
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out.']);
    }
}
