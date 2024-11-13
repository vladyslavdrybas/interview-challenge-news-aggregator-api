<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
