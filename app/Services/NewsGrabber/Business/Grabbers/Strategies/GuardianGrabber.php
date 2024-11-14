<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Grabbers\Strategies;

use App\Services\NewsGrabber\Business\Detector\StrategyNames;
use App\Services\NewsGrabber\Business\Grabbers\IGrabber;
use App\Services\NewsGrabber\DTO\FetchedArticleDTO;
use App\Services\NewsGrabber\DTO\FetchedNewsDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function collect;
use function sprintf;

class GuardianGrabber implements IGrabber
{
    protected ?string $apiUrl = null;
    protected ?string $apiKey = null;

    public function __construct()
    {
        $this->apiKey = env('GUARDIAN_API_KEY');
        $this->apiUrl = env('GUARDIAN_API_BASE_URL');
    }

    // TODO add limits to reduce requests for each api based on the "api activity"
    // TODO get categories, authors, etc.
    public function fetch(NewsSourceDTO $source): FetchedNewsDTO
    {
        if (StrategyNames::tryFrom($source->strategy) !== StrategyNames::GUARDIAN) {
            throw new Exception(sprintf('%s is a wrong strategy for source: %s', StrategyNames::GUARDIAN->value, $source->strategy));
        }

        $url = $this->apiUrl ?? $source->url ?? null;
        $apikey =  $this->apiKey ?? $source->apikey ?? null;

        if (empty($apikey) || empty($url)) {
                Log::warning(sprintf('%s cannot take data via %s. Empty input values.', StrategyNames::GUARDIAN->value, $source->strategy));
                return new FetchedNewsDTO([]);
        }

        $response = Http::get(
            $url,
            [
                'api-key' => $apikey,
                'page-size' => 50,
                'page' => 1,
                'order-by' => 'newest',
            ]
        );

        if (!$response->successful()) {
            Log::warning(sprintf('%s cannot take data via %s', StrategyNames::GUARDIAN->value, $source->strategy));
            return new FetchedNewsDTO([]);
        }

        $articles = $response->json('response.results');

        $news = collect($articles)->map(function ($article) use ($source) {
            return new FetchedArticleDTO(
                title: $article['webTitle'],
                content: $article['webUrl'],
                sourceSlug: $source->slug,
                publishedAt: Carbon::parse($article['webPublicationDate'])
            );
        })->toArray();

        return new FetchedNewsDTO($news);
    }
}
