<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

// TODO add link to origin
class Article extends Model
{
    use HasFactory, Searchable;

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

    public function toSearchableArray(): array
    {
        // All model attributes are made searchable
        $array = $this
            ->with(['categories', 'authors', 'source'])
            ->where('id', '=', $this->id)
            ->where('is_hidden', '=', 0)
            ->whereNotNull('news_source_id')
            ->first()
            ->toArray();

        $searchable = [
            'title' => $array['title'],
            'content' => $array['content'],
            'published_at' => $array['published_at'],
            'created_at' => $array['created_at'],
            'categories' => $array['categories'],
            'authors' => $array['authors'],
            'source' => $array['source'],
        ];

        return $searchable;
    }
}
