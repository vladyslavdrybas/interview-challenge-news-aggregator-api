<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    public const CREATE_RULES = [
        'title' => 'required|string|max:255',
        'slug' => 'string|max:255|unique:user_preferences',
        'categories' => 'array|exists:news_categories,id',
        'authors' => 'array|exists:news_authors,id',
        'sources' => 'array|exists:news_sources,id',
    ];

    public const UPDATE_RULES = self::CREATE_RULES;

    protected $table = 'user_preferences';

    protected $fillable = ['user_id', 'title', 'slug'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'slug',
    ];

    // Many-to-One relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'user_preference_news_category');
    }

    public function authors()
    {
        return $this->belongsToMany(NewsAuthor::class, 'user_preference_news_author');
    }

    public function sources()
    {
        return $this->belongsToMany(NewsSource::class, 'user_preference_news_source');
    }
}
