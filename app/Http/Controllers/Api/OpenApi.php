<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: '1',
        description: 'The challenge is to build a RESTful API for a news aggregator service that pulls articles from various sources and provides endpoints for a frontend application to consume.',
        title: 'Challenge News Aggregator API',
    ),
    servers: [
        new OA\Server(
            url: 'http://localhost:8090/api/v1',
            description: 'Main API'
        ),
    ],
    components: new OA\Components(
        responses: [
            'BadRequest' => new OA\Response(
                response: 400,
                description: 'Bad Request',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Bad Request')
                        ],
                        type: 'object'
                    )
                )
            ),
            'Unauthorized' => new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Unauthorized')
                        ],
                        type: 'object'
                    )
                )
            ),
            'NotFound' => new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Not found')
                        ],
                        type: 'object'
                    )
                )
            ),
            'UnprocessableEntity' => new OA\Response(
                response: 422,
                description: 'Unprocessable Entity',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Unprocessable Entity'),
                            new OA\Property(property: 'errors', type: 'object')
                        ],
                        type: 'object'
                    )
                )
            )
        ],
        securitySchemes: [
            'bearerHttpAuthentication' => new OA\SecurityScheme(
                securityScheme: 'bearerHttpAuthentication',
                type: 'http',
                description: 'Bearer api token authentication',
                name: 'Authorization',
                in: 'header',
                bearerFormat: 'JWT',
                scheme: 'Bearer'
            ),
        ]
    )
)]
class OpenApi
{
}
