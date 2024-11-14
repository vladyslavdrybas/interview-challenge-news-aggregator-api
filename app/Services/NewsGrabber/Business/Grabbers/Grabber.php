<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\Business\Grabbers;

use App\Services\NewsGrabber\DTO\FetchedNewsDto;
use App\Services\NewsGrabber\DTO\NewsSourceDTO;

class Grabber
{
    public function __construct()
    {

    }

    public function fetch(NewsSourceDTO $source): FetchedNewsDto
    {
        return new FetchedNewsDto([]);
    }
}
