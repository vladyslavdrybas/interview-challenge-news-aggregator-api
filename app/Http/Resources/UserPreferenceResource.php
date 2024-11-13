<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserPreferencesResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsCategoryResource')),
        new OA\Property(property: 'authors', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsAuthorResource')),
        new OA\Property(property: 'sources', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsSourceResource'))
    ],
    type: 'object'
)]
class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'categories' => NewsCategoryResource::collection($this->whenLoaded('categories')),
            'authors' => NewsAuthorResource::collection($this->whenLoaded('authors')),
            'sources' => NewsSourceResource::collection($this->whenLoaded('sources')),
        ];
    }
}
