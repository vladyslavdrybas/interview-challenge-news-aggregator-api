<?php

namespace App\Jobs;

use App\Services\NewsGrabber\DTO\FetchedArticleDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use App\Services\NewsGrabber\NewsGrabberFacade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewsSourceArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct( protected NewsSourceDTO $dto) {}

    public function handle(NewsGrabberFacade $newsGrabber): void {
        $news = $newsGrabber->fetchNews($this->dto);
        foreach ($news->news as $fetchedArticleDto) {
            PrepareToStoreFetchedArticleJob::dispatch($fetchedArticleDto)->onQueue('news_storing');
        }
    }
}
