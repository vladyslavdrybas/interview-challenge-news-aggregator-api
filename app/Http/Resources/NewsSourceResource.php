<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewsSourceResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 11),
        new OA\Property(property: 'title', type: 'string', example: 'TechCrunch')
    ],
    type: 'object'
)]
class NewsSourceResource extends JsonResource
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
        ];
    }
}
