<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\DTO;

class FetchedNewsDTO
{
    public function __construct(
        /** @var array<FetchedArticleDTO> $news*/
        public array $news
    ) {}
}
