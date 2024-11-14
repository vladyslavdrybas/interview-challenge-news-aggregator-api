<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Grabbers;

use App\Services\NewsGrabber\DTO\FetchedNewsDTO;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;

interface IGrabber
{
    public function fetch(NewsSourceDTO $source): FetchedNewsDTO;
}
