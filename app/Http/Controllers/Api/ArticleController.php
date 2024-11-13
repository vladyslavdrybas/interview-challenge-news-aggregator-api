<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function show(int $id)
    {
        $article = Article::with(['categories', 'authors', 'source'])->findOrFail($id);

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new ArticleResource($article),
            ]
        );
    }
}
