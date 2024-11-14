<?php

namespace App\Jobs;

use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewsSourceArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected NewsSourceDTO $dto
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        PrepareToStoreFetchedArticleJob::dispatch()->onQueue('news_storing');
    }
}