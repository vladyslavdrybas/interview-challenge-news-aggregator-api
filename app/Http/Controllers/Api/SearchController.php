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
use function config;
use function implode;
use function min;
use function response;

class SearchController extends Controller
{
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
                'per_page' => $data->perPage(),
            ],
            'data' => ArticleResource::collection($data),
        ]);
    }
}
