<?php
declare(strict_types=1);

namespace App\Services\NewsGrabber\DTO;

class NewsSourceDTO
{
    public function __construct(
        public string $url,
        public string $apikey,
        public ?string $slug = null,
        public ?string $strategy = null,
    ) {}
}
