<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\DTO;

use DateTimeInterface;

class FetchedArticleDTO
{
    public function __construct(
        public string $title,
        public ?string $content = null,
        public ?string $sourceSlug = null,
        public ?DateTimeInterface $publishedAt = null,
    ) {}
}
