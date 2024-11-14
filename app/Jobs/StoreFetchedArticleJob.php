<?php

namespace App\Jobs;

use App\DTO\ArticleDTO;
use App\Models\Article;
use App\Models\NewsSource;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreFetchedArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected ArticleDTO $dto) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = NewsSource::query();
        if (null !== $this->dto->sourceId) {
            $sourceId = $query->where('id', '=', $this->dto->sourceId)
                ->pluck('id')->first();
            ;
        } else {
            $sourceId = $query->where('slug', '=', $this->dto->sourceSlug)
                ->pluck('id')->first();
            ;
        }

        if (null === $sourceId) {
            throw new Exception(sprintf('Undefined news source. slug: %s; id: %s', $this->dto->sourceSlug, $this->dto->sourceId));
        }

        // TODO add validation for article
        $article = new Article();
        $article->source()->associate($sourceId);
        $article->title = $this->dto->title;
        $article->content = $this->dto->content;
        $article->published_at = $this->dto->publishedAt;

        $article->save();
    }
}
