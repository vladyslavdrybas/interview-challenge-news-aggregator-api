<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewsSource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'slug', type: 'string', example: 'news-api-1'),
        new OA\Property(property: 'title', type: 'string', example: 'News API 1'),
        new OA\Property(property: 'created_at', type: 'datetime', example: '2024-11-13T13:05:45.000000Z'),
    ],
    type: 'object'
)]
class NewsSource extends Model
{
    use HasFactory;

    public const CREATE_RULES = [
        'title' => 'required|string|max:255',
        'base_url' => 'required|string',
        'apikey' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
    ];

    protected $table = 'news_sources';

    protected $fillable = [];
    protected $guarded = [];

    protected $hidden = [
        'updated_at',
        'apikey',
        'base_url',
    ];
}
