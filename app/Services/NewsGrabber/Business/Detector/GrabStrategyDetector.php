<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Detector;

use App\Services\NewsGrabber\DTO\NewsSourceDTO;
use function str_contains;

class GrabStrategyDetector
{
    public function __construct(){}

    public function detect(NewsSourceDTO $source): string
    {
        return match (true) {
            null !== StrategyNames::tryFrom($source->strategy ?? '')
            || str_contains($source->slug, 'theguardian')
            || str_contains($source->url, 'guardianapis') => StrategyNames::GUARDIAN->value,

            default => StrategyNames::UNDEFINED->value
        };
    }
}
