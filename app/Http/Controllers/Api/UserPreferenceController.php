<?php

namespace App\Http\Controllers\Api;

use App\Constants\AnswerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Http\Requests\UserUpdatePreferenceRequest;
use App\Http\Resources\UserPreferenceResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function auth;
use function config;
use function min;
use function response;

class UserPreferenceController extends Controller
{
    #[OA\Get(
        path: '/user/preferences',
        summary: 'Retrieve the user preferences list.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['User Preferences'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved user preferences list.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        type: 'array',
                        items: new OA\Items(ref: '#/components/schemas/UserPreferencesResource')
                    )
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
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

    #[OA\Post(
        path: '/user/preferences',
        summary: 'Create a new user preference.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/UserPreferenceRequest')
            )
        ),
        tags: ['User Preferences'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Successfully created a new user preference.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/UserPreferencesResource')
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
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

        $data = auth()->user()->preferences()
            ->with(['categories', 'authors', 'sources'])
            ->findOrFail($data->id);

        return response()->json(
            [
                'type' => AnswerType::OBJECT->value,
                'data' => new UserPreferenceResource($data),
            ],
            201
        );
    }

    #[OA\Get(
        path: '/user/preferences/{id}',
        summary: 'Retrieve a specific user preference.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['User Preferences'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/id'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved the user preference.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/UserPreferencesResource')
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
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

    #[OA\Put(
        path: '/user/preferences/{id}',
        summary: 'Update an existing user preference.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(ref: '#/components/schemas/UserUpdatePreferenceRequest')
            )
        ),
        tags: ['User Preferences'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/id'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully updated the user preference.',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(ref: '#/components/schemas/UserPreferencesResource')
                )
            ),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
        ]
    )]
    public function update(UserUpdatePreferenceRequest $request, string $id)
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

    #[OA\Delete(
        path: '/user/preferences/{id}',
        summary: 'Delete a user preference.',
        security: [
            ['bearerHttpAuthentication' => new OA\SecurityScheme(ref: '#/components/securitySchemes/bearerHttpAuthentication')],
        ],
        tags: ['User Preferences'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/id'),
        ],
        responses: [
            new OA\Response(ref: '#/components/responses/204', response: 204),
            new OA\Response(ref: '#/components/responses/400', response: 400),
            new OA\Response(ref: '#/components/responses/401', response: 401),
            new OA\Response(ref: '#/components/responses/404', response: 404),
        ]
    )]
    public function destroy(int $id)
    {
        $preferences = auth()->user()->preferences()->findOrFail($id);
        $preferences->delete();

        return response()->json(null, 204);
    }
}
