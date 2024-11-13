<?php

namespace App\Observers;

use App\Models\NewsSource;
use Illuminate\Support\Str;

class NewsSourceObserver
{
    public function saving(NewsSource $newsSource): void
    {
        if (null === ($newsSource->slug ?? null)
            && null !== ($newsSource->title ?? null)
        ) {
            $newsSource->slug = Str::slug($newsSource->title);
        }
    }
}
