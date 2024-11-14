<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserFeedRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use function array_map;
use function array_unique;
use function config;
use function count;
use function min;
use function response;

class UserFeedController extends Controller
{
    #[OA\Get(
        path: '/user/feed',
        summary: 'Retrieve articles with pagination and user preferences.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/per_page'),
            new OA\Parameter(ref: '#/components/parameters/user_preferences[]'),
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
    public function index(UserFeedRequest $request)
    {
        $validated = $request->validated();

        // TODO move to a filter builder
        // TODO add search data in redis
        // TODO catch issue if user does not send preferences. must be some default response.
        $query = Article::query();

        $preferenceIds = $validated['user_preferences'];

        // Subquery 1: Filtering by Categories
        $categorySubquery = DB::table('articles as a')
            ->select('a.id')
            ->join('article_news_category as anc', 'anc.article_id', '=', 'a.id')
            ->whereExists(function ($query) use ($preferenceIds) {
                $query->select(DB::raw(1))
                    ->from('user_preference_news_category as upnc')
                    ->whereRaw('upnc.news_category_id = anc.news_category_id')
                    ->whereIn('upnc.user_preference_id', $preferenceIds);
            });

        // Subquery 2: Filtering by Authors
        $authorSubquery = DB::table('articles as a')
            ->select('a.id')
            ->join('article_news_author as ana', 'ana.article_id', '=', 'a.id')
            ->whereExists(function ($query) use ($preferenceIds) {
                $query->select(DB::raw(1))
                    ->from('user_preference_news_author as upna')
                    ->whereRaw('upna.news_author_id = ana.news_author_id')
                    ->whereIn('upna.user_preference_id', $preferenceIds);
            });

        // Subquery 3: Filtering by News Source
        $sourceSubquery = DB::table('articles as a')
            ->select('a.id')
            ->whereExists(function ($query) use ($preferenceIds) {
                $query->select(DB::raw(1))
                    ->from('user_preference_news_source as upns')
                    ->whereRaw('upns.news_source_id = a.news_source_id')
                    ->whereIn('upns.user_preference_id', $preferenceIds);
            });

        $articleIds = array_map(
            fn($i) => $i->id,
            $categorySubquery->union($authorSubquery)
                ->union($sourceSubquery)
                ->get()
                ->toArray()
        );

        $query->whereIn('id', $articleIds);

        if ($request->has('sort')) {
            $query->orderBy('created_at', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = min($request->query('per_page', config('app.pagination.perPage')), config('app.pagination.perPageMax'));

        $data = $query
            ->whereNotNull('news_source_id')
            ->where('is_hidden', '=', 0)
            ->with(['categories', 'authors', 'source'])
            ->paginate($perPage);

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
}
