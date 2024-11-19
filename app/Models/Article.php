<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

// TODO add link to origin
class Article extends Model
{
    use HasFactory, Searchable;

    // Define the table name (optional if it's the same as the model name pluralized)
    protected $table = 'articles';

    // Specify the fillable attributes
    protected $fillable = ['title', 'content', 'published_at'];
    protected $searchableAttributes = ['title', 'content'];

    public function source(): BelongsTo
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

    public function shouldBeSearchable(): bool
    {
        return null !== $this->news_source_id && $this->is_hidden === 0;
    }

    public function toSearchableArray(): array
    {
        $searchable = [
            'id' => (int) $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,

            'categories' => $this->categories->map(fn($entity) => $entity->toSearchableArray())->toArray(),
            'authors' => $this->authors->map(fn($entity) => $entity->toSearchableArray())->toArray(),
            'source' => $this->source->toSearchableArray(),
        ];

        return $searchable;
    }
}
