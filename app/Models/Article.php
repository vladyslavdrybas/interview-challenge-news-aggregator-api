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

    // TODO many-many Category
    // TODO many-many Author
    // TODO many-one NewsSource
}
