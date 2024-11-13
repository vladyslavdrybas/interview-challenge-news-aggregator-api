<?php
declare(strict_types=1);

namespace App\ModelFactories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class AbstractModelFactory
{
    public const VALIDATION_RULES = [];
    public const MODEL_CLASS = '';

    protected function validate(array $data): void
    {
        $validator = Validator::make($data, static::VALIDATION_RULES);

        if ($validator->fails()) {
            // If validation fails, throw ValidationException with errors
            throw new ValidationException($validator);
        }
    }

    protected function filterProperties(array $data): array
    {
        return array_intersect_key($data,static::VALIDATION_RULES);
    }

    protected function construct(array $data): Model
    {
        $data = $this->filterProperties($data);
        $this->validate($data);

        $model = static::MODEL_CLASS;
        if (empty($model)) {
            throw new Exception('Model class not found.');
        }

        $factoryProduct = new $model();
        $factoryProduct->fill($data);

        return $factoryProduct;
    }
}
