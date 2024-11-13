<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Models\NewsSource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class NewsSourceController extends Controller
{
    #[OA\Get(
        path: '/news-sources',
        summary: 'Retrieve all news sources with pagination.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Sources'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/per_page')
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved news sources.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'type', type: 'string', example: 'list'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsSource')),
                            new OA\Property(property: 'pagination', ref: '#/components/schemas/PaginationInfo')
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
    public function index(Request $request)
    {

        $perPage = $request->query('per_page', config('app.pagination.perPage'));

        $newsSources = NewsSource::paginate($perPage);

        return response()->json([
            'type' => AnswerType::LIST->value,
            'pagination' => [
                'current_page' => $newsSources->currentPage(),
                'total_pages' => $newsSources->lastPage(),
                'total_items' => $newsSources->total(),
            ],
            'data' => $newsSources->items(),
        ]);
    }
}
