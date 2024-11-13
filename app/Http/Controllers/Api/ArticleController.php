<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;
use function response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
