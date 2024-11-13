<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class ApiHomeController extends Controller
{
    #[OA\Get(
        path: '/',
        description: 'Returns a welcome message for the API',
        summary: 'Welcome Message',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Home'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Welcome message',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Welcome to the API!')
                    ]
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
    public function index()
    {
        return response()->json([
            'message' => 'Welcome to the API!'
        ]);
    }
}
