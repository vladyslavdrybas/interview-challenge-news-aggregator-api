<?php
declare(strict_types=1);

namespace App\DTO;

class FetchNewsSourceDTO
{
    public function __construct(
        public int $source_id,
        public string $url,
        public string $apikey,
        public string $strategy,
    ) {}
}
