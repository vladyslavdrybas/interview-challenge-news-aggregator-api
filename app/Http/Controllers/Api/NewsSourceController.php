<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Models\NewsSource;
use Illuminate\Http\Request;

class NewsSourceController extends Controller
{
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
