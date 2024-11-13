<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function config;
use function response;

class NewsCategoryController extends Controller
{
    #[OA\Get(
        path: '/news-categories',
        summary: 'Retrieve all news categories with pagination.',
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
                description: 'Successfully retrieved news categories.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'type', type: 'string', example: 'list'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsCategory')),
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

        $data = NewsCategory::paginate($perPage);

        return response()->json([
            'type' => AnswerType::LIST->value,
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'total_items' => $data->total(),
            ],
            'data' => $data->items(),
        ]);
    }
}
