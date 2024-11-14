<?php

namespace App\Jobs;

use App\DTO\FetchNewsSourceDTO;
use App\Models\NewsSource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateFetchNewsSourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Process ' . __METHOD__);

//        $apis = NewsSource::query()->whereNotNull('base_url')->whereNotNull('apikey')->get();

//        self::dispatch(new FetchNewsSourceArticlesJob);
        FetchNewsSourceArticlesJob::dispatch()->onQueue('news_fetching');
    }
}
