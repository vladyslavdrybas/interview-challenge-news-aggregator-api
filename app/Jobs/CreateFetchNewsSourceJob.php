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
        // TODO just logging example. remove it anytime.
        Log::info('Just fyi some random log.' . __METHOD__);

        $apis = NewsSource::query()->whereNotNull('base_url')->whereNotNull('apikey')->get();

        foreach ($apis as $api) {
            $newsSourceDTO = new NewsSourceDTO($api->base_url, $api->apikey, $api->slug, '');
            $newsSourceDTO = $newsGrabber->setGrabStrategy($newsSourceDTO);
            if ($newsGrabber->canGrab($newsSourceDTO)) {
                Log::info(json_encode($newsSourceDTO));
                FetchNewsSourceArticlesJob::dispatch($newsSourceDTO)->onQueue('news_fetching');
            }
        }
    }
}
