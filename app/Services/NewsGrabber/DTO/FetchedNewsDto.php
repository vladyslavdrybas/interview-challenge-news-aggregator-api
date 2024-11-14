<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\DTO;

class FetchedNewsDto
{
    public function __construct(
        public array $news
    ) {}
}
