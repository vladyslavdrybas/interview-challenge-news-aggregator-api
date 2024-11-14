<?php

use App\Jobs\CreateFetchNewsSourceJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CreateFetchNewsSourceJob, 'news_fetching', 'redis')->everyTenSeconds();
