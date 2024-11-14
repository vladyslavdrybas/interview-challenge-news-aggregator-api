<?php

namespace App\Jobs;

use App\DTO\ArticleDTO;
use App\Services\NewsGrabber\DTO\FetchedArticleDTO;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PrepareToStoreFetchedArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected FetchedArticleDTO $dto) {}

    public function handle(): void
    {
        $articleDto = new ArticleDTO(
            $this->dto->title,
            $this->dto->sourceSlug,
            $this->dto->content,
            $this->dto->publishedAt
        );

        StoreFetchedArticleJob::dispatch($articleDto)->onQueue('news_storing');
    }
}
