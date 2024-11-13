<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;
use function config;
use function response;

class ArticleController extends Controller
{
    #[OA\Get(
        path: '/articles',
        summary: 'Retrieve articles with pagination and optional filters.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/per_page'),
            new OA\Parameter(ref: '#/components/parameters/categories[]'),
            new OA\Parameter(ref: '#/components/parameters/sources[]'),
            new OA\Parameter(ref: '#/components/parameters/authors[]'),
            new OA\Parameter(ref: '#/components/parameters/sort'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved articles.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'type', type: 'string', example: 'list'),
                            new OA\Property(property: 'pagination', ref: '#/components/schemas/PaginationInfo'),
                            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ArticleResource')),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/422', response: 422),
            new OA\Response(ref: '#/components/responses/404', response: 404),
        ]
    )]
    public function index(ArticleListRequest $request)
    {
        $request->validated();

        // TODO move to a filter builder
        $query = Article::query();

        if ($request->has('categories')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('id', $request->categories);
            });
        }

        if ($request->has('authors')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->whereIn('id', $request->authors);
            });
        }

        if ($request->has('sources')) {
            $query->whereIn('news_source_id', $request->sources);
        }

        if ($request->has('sort')) {
            $query->orderBy('created_at', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = min($request->query('per_page', config('app.pagination.perPage')), config('app.pagination.perPageMax'));
        $data = $query->with(['categories', 'authors', 'source'])->where('is_hidden', '=', 0)->paginate($perPage);

        // Return paginated results using ArticleResource
        return response()->json([
            'type' => AnswerType::LIST->value,
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'total_items' => $data->total(),
            ],
            'data' => ArticleResource::collection($data),
        ]);
    }

    #[OA\Get(
        path: '/articles/{id}',
        summary: 'Retrieve a specific article by ID with all relationships',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the article',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved article.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/ArticleResource')
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/404', response: 404),
        ]
    )]
    public function show(int $id)
    {
        try{
            $article = Article::with(['categories', 'authors', 'source'])->findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json(
                [
                    'type' => AnswerType::ERROR->value,
                    'message' => 'Not found.',
                ],
                404
            );
        }

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new ArticleResource($article),
            ]
        );
    }
}
