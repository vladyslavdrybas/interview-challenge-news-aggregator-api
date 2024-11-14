<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Grabbers\Strategies;

use App\Services\NewsGrabber\Business\Detector\StrategyNames;
use App\Services\NewsGrabber\Business\Grabbers\IGrabber;
use App\Services\NewsGrabber\DTO\FetchedArticleDTO;
use App\Services\NewsGrabber\DTO\FetchedNewsDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use DateTime;
use Exception;
use Illuminate\Support\Str;

class GuardianGrabber implements IGrabber
{
    public function fetch(NewsSourceDTO $source): FetchedNewsDTO
    {
        if (StrategyNames::tryFrom($source->strategy) !== StrategyNames::GUARDIAN) {
            throw new Exception(sprintf('%s is a wrong strategy for source: %s', StrategyNames::GUARDIAN->value, $source->strategy));
        }

        // TODO add validation before writing
        $news = [
            new FetchedArticleDTO(
                title: StrategyNames::GUARDIAN->value . '-' . Str::random() . uniqid('', true),
                sourceSlug: $source->slug,
                publishedAt: new DateTime()
            ),
        ];

        throw new Exception('TODO ' . __METHOD__);

        return new FetchedNewsDTO($news);
    }
}
