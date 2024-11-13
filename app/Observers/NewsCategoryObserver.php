<?php

namespace App\Observers;

use App\Models\NewsCategory;
use Illuminate\Support\Str;

class NewsCategoryObserver
{
    public function saving(NewsCategory $newsCategory): void
    {
        if (empty($newsCategory->slug)
            && !empty($newsCategory->title)
        ) {
            $newsCategory->slug = Str::slug($newsCategory->title);
        }
    }
}
