<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArticleResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Particle launches an AI news app to help publishers, instead of just stealing their work'),
        new OA\Property(property: 'content', type: 'string', example: 'From the consumers’ perspective, the core idea behind Particle is to help readers better understand the news with the help of AI technology. More than just summarizing stories into key bullet points for quick catch-ups, Particle offers a variety of clever features that let you approach the news in different ways.'),
        new OA\Property(property: 'published_at', type: 'string', format: 'date-time', example: '2024-11-13T13:05:45.000000Z', nullable: true),
        new OA\Property(property: 'source', ref: '#/components/schemas/NewsSourceResource'),
        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsCategoryResource')),
        new OA\Property(property: 'authors', type: 'array', items: new OA\Items(ref: '#/components/schemas/NewsAuthorResource'))
    ],
    type: 'object'
)]
class ArticleResource extends JsonResource
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
            'content' => $this->content,
            'published_at' => $this->published_at,
            'source' => new NewsSourceResource($this->whenLoaded('source')),
            'categories' => NewsCategoryResource::collection($this->whenLoaded('categories')),
            'authors' => NewsAuthorResource::collection($this->whenLoaded('authors')),
        ];
    }
}
