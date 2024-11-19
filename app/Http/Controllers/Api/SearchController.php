<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Meilisearch\Endpoints\Indexes;
use OpenApi\Attributes as OA;
use function config;
use function implode;
use function min;
use function response;

class SearchController extends Controller
{
    #[OA\Get(
        path: '/articles/search',
        summary: 'Search articles with pagination and optional filters.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/per_page'),
            new OA\Parameter(ref: '#/components/parameters/sources[]'),
            new OA\Parameter(ref: '#/components/parameters/keywords[]'),
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
    public function __invoke(ArticleListRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $searchableQuery = Article::search(
            query: implode(' ', $request->input('keywords', [])),
            callback: function (Indexes $meilisearch, string $query, array $options) use ($request) {
//                dump($meilisearch->getSettings());
//                dump($options);
//                exit;

                return $meilisearch->search(
                    query: $query,
                    searchParams: $options,
                    options: $options,
                );
            }
        );


        if ($request->has('sources')) {
            $searchableQuery = $searchableQuery->whereIn('source.id', $request->sources);
        }

        $perPage = min($request->query('per_page',
            config('app.pagination.perPage')),
                    config('app.pagination.perPageMax'));

        $data = $searchableQuery
            ->query(fn (Builder $query) => $query->with(['categories', 'authors', 'source']))
            ->paginate($perPage);

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
}
