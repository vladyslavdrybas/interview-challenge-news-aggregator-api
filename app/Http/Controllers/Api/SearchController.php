<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Meilisearch\Endpoints\Indexes;
use function config;
use function implode;
use function min;
use function response;

class SearchController extends Controller
{
    public function __invoke(ArticleListRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $searchableQuery = Article::search('');


        $perPage = min($request->query('per_page',
            config('app.pagination.perPage')),
                    config('app.pagination.perPageMax'));
        $page = $request->query('per_page', 1);
        $data = $searchableQuery->paginate($perPage, ['page' => $page]);

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
