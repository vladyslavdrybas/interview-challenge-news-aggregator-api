<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1',
    description: 'API for news aggregator challenge.',
    title: 'Laravel API'
)]

#[OA\Server(
    url: 'http://localhost:8090/api/v1',
    description: 'Main API'
)]

#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    description: 'Bearer api token authentication',
    name: 'bearerAuth',
    in: 'header',
    bearerFormat: 'bearerToken',
    scheme: 'bearer'
)]

class ApiHomeController extends Controller
{
    #[OA\Get(
        path: '/',
        description: 'Returns a welcome message for the API',
        summary: 'Welcome Message',
        security: [
            'bearerAuth'
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
            )
        ]
    )]
    public function index()
    {
        return response()->json([
            'message' => 'Welcome to the API!'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
