<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use function auth;

class UserPreferenceController extends Controller
{
    public function index()
    {
        $preferences = auth()->user()->preferences()->get();

        return response()->json($preferences);
    }

    public function store(UserPreferenceRequest $request)
    {
        $validated = $request->validated();

        $preferences = auth()->user()->preferences()->create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
        ]);

        $preferences->categories()->attach($validated['categories']);
        $preferences->authors()->attach($validated['authors']);
        $preferences->sources()->attach($validated['sources']);

        return response()->json($preferences, 201);
    }

    public function show(int $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);

        return response()->json($preferences);
    }

    public function update(UserPreferenceRequest $request, string $id)
    {
        $validated = $request->validated();

        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->update($validated);

        $preferences->categories()->sync($validated['categories']);
        $preferences->authors()->sync($validated['authors']);
        $preferences->sources()->sync($validated['sources']);

        return response()->json($preferences);
    }

    public function destroy(int $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->delete();

        return response()->json(null, 204);
    }
}
