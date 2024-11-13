<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function index()
    {
        return response()->json(
            auth()->user()->preferences()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(UserPreference::CREATE_RULES);

        $preferences = auth()->user()->preferences()->create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
        ]);

        $preferences->categories()->attach($validated['categories']);
        $preferences->authors()->attach($validated['authors']);
        $preferences->sources()->attach($validated['sources']);

        return response()->json($preferences, 201);
    }

    public function show(string $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);

        return response()->json($preferences);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate(UserPreference::CREATE_RULES);

        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->update($validated);

        $preferences->categories()->sync($validated['categories']);
        $preferences->authors()->sync($validated['authors']);
        $preferences->sources()->sync($validated['sources']);

        return response()->json($preferences);
    }

    public function destroy(string $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->delete();
        return response()->json(null, 204);
    }
}
