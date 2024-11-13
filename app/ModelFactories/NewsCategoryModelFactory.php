<?php
declare(strict_types=1);

namespace App\ModelFactories;

use App\Models\NewsCategory;
use Illuminate\Support\Str;

class NewsCategoryModelFactory extends AbstractModelFactory
{
    public const MODEL_CLASS = NewsCategory::class;
    public const VALIDATION_RULES = NewsCategory::CREATE_RULES;

    public function __invoke(array $data): NewsCategory
    {
        /** @var NewsCategory $model */
        $model = $this->construct($data);

        $model->title = trim($model->title);

        if (empty($model->slug)) {
            $model->slug = Str::slug($model->title);
        }

        return $model;
    }
}
