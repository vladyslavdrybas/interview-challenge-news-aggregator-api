<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';

    protected $fillable = ['user_id', 'title', 'slug'];

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
