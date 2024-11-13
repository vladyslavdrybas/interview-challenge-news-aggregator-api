<?php

namespace App\Observers;

use App\Models\UserPreference;
use Illuminate\Support\Str;

class UserPreferenceObserver
{
    public function saving(UserPreference $userPreference): void
    {
        if (empty($userPreference->slug)
            && !empty($userPreference->title)
        ) {
            $userPreference->slug = Str::slug($userPreference->title);
        }
    }
}
