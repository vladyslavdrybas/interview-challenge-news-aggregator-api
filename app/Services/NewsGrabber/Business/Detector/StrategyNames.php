<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Detector;

enum StrategyNames: string
{
    CASE UNDEFINED = 'undefined';
    CASE GUARDIAN = 'guardian';
}
