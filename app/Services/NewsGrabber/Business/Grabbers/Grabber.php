<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Grabbers;

use App\Services\NewsGrabber\Business\Detector\StrategyNames;
use App\Services\NewsGrabber\Business\Grabbers\Strategies\GuardianGrabber;
use App\Services\NewsGrabber\DTO\FetchedNewsDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;

class Grabber implements IGrabber
{
    public function __construct() {}

    public function fetch(NewsSourceDTO $source): FetchedNewsDTO
    {
        return match (true) {
            $source->strategy === StrategyNames::GUARDIAN->value => (new GuardianGrabber())->fetch($source),
            default => new FetchedNewsDTO([])
        };
    }

    protected function fetchByStrategy(StrategyNames $strategy): FetchedNewsDTO
    {
        return new FetchedNewsDTO([]);
    }
}
