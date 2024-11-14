<?php
declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;

class ArticleDTO
{
    public function __construct(
        public string $title,
        public ?string $sourceSlug = null,
        public ?string $content = null,
        public ?DateTimeInterface $publishedAt = null,
        public ?int $sourceId = null,
    ) {}
}
