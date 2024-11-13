<?php

namespace App\Observers;

use App\Models\NewsAuthor;
use Illuminate\Support\Str;

class NewsAuthorObserver
{
    public function saving(NewsAuthor $newsAuthor): void
    {
        if (empty($newsAuthor->slug)
            && !empty($newsAuthor->full_name)
        ) {
            $newsAuthor->slug = Str::slug($newsAuthor->full_name);
        }
    }
}
