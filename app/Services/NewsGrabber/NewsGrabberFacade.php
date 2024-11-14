<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber;

use App\Services\NewsGrabber\Business\Detector\GrabStrategyDetector;
use App\Services\NewsGrabber\Business\Detector\StrategyNames;
use App\Services\NewsGrabber\Business\Grabbers\Grabber;
use App\Services\NewsGrabber\DTO\FetchedNewsDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;

class NewsGrabberFacade
{
    // can be moved to factory for customization
    public function __construct(
        protected Grabber $grabber,
        protected GrabStrategyDetector $grabberStrategyDetector,
    ) {}

    public function fetchNews(NewsSourceDTO $source): FetchedNewsDTO
    {
        return $this->grabber->fetch($source);
    }

    public function setGrabStrategy(NewsSourceDTO $source): NewsSourceDTO
    {
        $source->strategy = $this->grabberStrategyDetector->detect($source);

        return $source;
    }

    public function canGrab(NewsSourceDTO $source): bool
    {
        return $source->strategy !== StrategyNames::UNDEFINED->value;
    }
}
