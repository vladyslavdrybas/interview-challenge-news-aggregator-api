<?php

namespace App\Observers;

use App\Models\NewsSource;
use Illuminate\Support\Str;

class NewsSourceObserver
{
    public function saving(NewsSource $newsSource): void
    {
        if (empty($newsSource->slug)
            && !empty($newsSource->title)
        ) {
            $newsSource->slug = Str::slug($newsSource->title);
        }
    }
}
