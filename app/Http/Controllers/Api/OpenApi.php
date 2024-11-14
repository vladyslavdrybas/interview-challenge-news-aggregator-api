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
        schemas: [
            'PaginationInfo' => new OA\Schema(
                schema: 'PaginationInfo',
                properties: [
                    new OA\Property(property: 'current_page', type: 'integer', example: 1),
                    new OA\Property(property: 'total_pages', type: 'integer', example: 10),
                    new OA\Property(property: 'total_items', type: 'integer', example: 100),
                ],
                type: 'object'
            ),
            'IntegerArray' => new OA\Schema(
                schema: 'IntegerArray',
                type: 'array',
                items: new OA\Items(type: 'integer', example: 1)
            ),
            'StringArray' => new OA\Schema(
                schema: 'StringArray',
                type: 'array',
                items: new OA\Items(type: 'string', example: 'keyword')
            ),
        ],
        responses: [
            'NoContent' => new OA\Response(
                response: 204,
                description: 'No Content',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'No Content')
                        ],
                        type: 'object'
                    )
                )
            ),
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
            'Forbidden' => new OA\Response(
                response: 403,
                description: 'Forbidden',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
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
        parameters: [
            'page' => new OA\Parameter(
                name: 'page',
                description: 'Page number for pagination',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            'per_page' => new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 10)
            ),
            'categories' => new OA\Parameter(
                name: 'categories[]',
                description: 'Filter articles by categories (array of category IDs)',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: '#/components/schemas/IntegerArray')
            ),
            'sources' => new OA\Parameter(
                name: 'sources[]',
                description: 'Filter articles by news sources (array of source IDs)',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: '#/components/schemas/IntegerArray')
            ),
            'authors' => new OA\Parameter(
                name: 'authors[]',
                description: 'Filter articles by authors (array of author IDs)',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: '#/components/schemas/IntegerArray')
            ),
            'user_preferences' => new OA\Parameter(
                name: 'user_preferences[]',
                description: 'Apply user_preferences to filter articles',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: '#/components/schemas/IntegerArray')
            ),
            'keywords' => new OA\Parameter(
                name: 'keywords[]',
                description: 'Filter articles by keywords (array of string)',
                in: 'query',
                required: false,
                schema: new OA\Schema(ref: '#/components/schemas/StringArray')
            ),
            'sort' => new OA\Parameter(
                name: 'sort',
                description: 'Order result.',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['asc', 'desc'],
                    example: 'desc'
                )
            ),
            'start_at' => new OA\Parameter(
                name: 'start_at',
                description: 'Filter published after this date (YYYY-MM-DDTHH:mm:ssZ)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date-time', example: '2021-01-01T00:00:00Z')
            ),
            'end_at' => new OA\Parameter(
                name: 'end_at',
                description: 'Filter published before this date (YYYY-MM-DDTHH:mm:ssZ)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date-time', example: '2021-01-01T00:00:00Z')
            ),
            'id' => new OA\Parameter(
                name: 'id',
                description: 'The ID of the entity',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
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
