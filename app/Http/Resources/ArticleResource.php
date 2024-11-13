<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'news_source' => new NewsSourceResource($this->whenLoaded('source')),
            'categories' => NewsCategoryResource::collection($this->whenLoaded('categories')),
            'authors' => NewsAuthorResource::collection($this->whenLoaded('authors')),
        ];
    }
}
