<?php

namespace App\Jobs;

use App\Models\NewsSource;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use App\Services\NewsGrabber\NewsGrabberFacade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use function json_encode;

class CreateFetchNewsSourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(NewsGrabberFacade $newsGrabber): void
    {
        $apis = NewsSource::query()->whereNotNull('base_url')->whereNotNull('apikey')->get();

        foreach ($apis as $api) {
            $dto = new NewsSourceDTO($api->base_url, $api->apikey, $api->slug, '');
            $dto = $newsGrabber->setGrabStrategy($dto);
            if ($newsGrabber->canGrab($dto)) {
                Log::info(json_encode($dto));
                FetchNewsSourceArticlesJob::dispatch($dto)->onQueue('news_fetching');
            }
        }
    }
}
