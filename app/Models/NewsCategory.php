<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewsCategory',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'slug', type: 'string', example: 'it-backend'),
        new OA\Property(property: 'title', type: 'string', example: 'IT backend'),
    ],
    type: 'object'
)]
class NewsCategory extends Model
{
    use HasFactory;

    public const CREATE_RULES = [
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
    ];

    protected $table = 'news_categories';

    protected $fillable = [];
    protected $guarded = [];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function userPreferences()
    {
        return $this->belongsToMany(UserPreference::class, 'user_preference_news_category');
    }
}
