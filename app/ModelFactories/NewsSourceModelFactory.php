<?php
declare(strict_types=1);

namespace App\ModelFactories;

use App\Models\NewsSource;
use Illuminate\Support\Str;

class NewsSourceModelFactory extends AbstractModelFactory
{
    public const MODEL_CLASS = NewsSource::class;
    public const VALIDATION_RULES = NewsSource::CREATE_RULES;

    public function __invoke(array $data): NewsSource
    {
        /** @var NewsSource $newsSource */
        $newsSource = $this->construct($data);

        if (empty($newsSource->slug)) {
            $newsSource->slug = Str::slug($newsSource->title);
        }

        return $newsSource;
    }
}
