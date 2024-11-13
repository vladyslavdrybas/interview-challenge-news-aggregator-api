<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Define the table name (optional if it's the same as the model name pluralized)
    protected $table = 'articles';

    // Specify the fillable attributes
    protected $fillable = ['title', 'content', 'published_at'];

    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id', 'id');
    }

    public function authors()
    {
        return $this->belongsToMany(NewsAuthor::class);
    }

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class);
    }
}
