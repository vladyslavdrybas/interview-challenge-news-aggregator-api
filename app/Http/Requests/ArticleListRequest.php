<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ArticleListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return  [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:25',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
            'sources' => 'nullable|array',
            'sort' => 'nullable|in:asc,desc',
            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after:start_date',
            // just uncomment below if you want to restrict the search for non-existing entities
//            'categories.*' => 'nullable|integer|exists:news_categories,id',
//            'authors.*' => 'nullable|integer|exists:news_authors,id',
//            'sources.*' => 'nullable|integer|exists:news_sources,id',
        ];
    }

    public function messages(): array
    {
        return  [
            'categories.*' => 'Category not found.',
            'authors.*' => 'Author not found.',
            'sources.*' => 'Source not found.',
        ];
    }
}
