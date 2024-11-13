<?php
declare(strict_types=1);

namespace App\ModelFactories;

use App\Models\NewsAuthor;
use Illuminate\Support\Str;

class NewsAuthorModelFactory extends AbstractModelFactory
{
    public const MODEL_CLASS = NewsAuthor::class;
    public const VALIDATION_RULES = NewsAuthor::CREATE_RULES;

    public function __invoke(array $data): NewsAuthor
    {
        /** @var NewsAuthor $model */
        $model = $this->construct($data);

        if (empty($model->slug)) {
            $model->slug = Str::slug($model->full_name);
        }

        return $model;
    }
}
