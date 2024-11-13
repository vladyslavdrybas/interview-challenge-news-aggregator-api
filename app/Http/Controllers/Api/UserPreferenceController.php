<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use Illuminate\Http\Request;
use function auth;
use function config;
use function min;
use function response;

class UserPreferenceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min($request->query('per_page', config('app.pagination.perPage')), config('app.pagination.perPageMax'));

        $data = auth()->user()->preferences()
            ->with(['categories', 'authors', 'sources'])
            ->paginate($perPage);

        return response()->json([
            'type' => AnswerType::LIST->value,
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'total_items' => $data->total(),
            ],
            'data' => UserPreferenceResource::collection($data),
        ]);
    }

    public function store(UserPreferenceRequest $request)
    {
        $validated = $request->validated();

        $data = auth()->user()->preferences()
            ->create([
                'title' => $validated['title'],
                'slug' => $validated['slug'] ?? null,
            ]);

        $data->categories()->attach($validated['categories']);
        $data->authors()->attach($validated['authors']);
        $data->sources()->attach($validated['sources']);

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new UserPreferenceResource($data),
            ],
            201
        );
    }

    public function show(int $id)
    {
        $data = auth()->user()->preferences()
            ->with(['categories', 'authors', 'sources'])
            ->findOrFail($id);

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new UserPreferenceResource($data),
            ],
        );
    }

    public function update(UserPreferenceRequest $request, string $id)
    {
        $validated = $request->validated();

        $data = auth()->user()->preferences()
            ->findOrFail($id);

        $data->update($validated);

        $data->categories()->sync($validated['categories']);
        $data->authors()->sync($validated['authors']);
        $data->sources()->sync($validated['sources']);

        $data = auth()->user()->preferences()
            ->with(['categories', 'authors', 'sources'])
            ->findOrFail($id);

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new UserPreferenceResource($data),
            ],
        );
    }

    public function destroy(int $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->delete();

        return response()->json(null, 204);
    }
}
