<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NewsAuthor',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'slug', type: 'string', example: 'neil-degrasse-tyson'),
        new OA\Property(property: 'full_name', type: 'string', example: 'Neil deGrasse Tyson'),
    ],
    type: 'object'
)]
class NewsAuthor extends Model
{
    use HasFactory;

    public const CREATE_RULES = [
        'full_name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
    ];

    protected $table = 'news_authors';

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
        return $this->belongsToMany(UserPreference::class, 'user_preference_news_author');
    }
}
