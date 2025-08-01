<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ArticleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('article');

        $required = $this->hasMainLanguage() ? 'required' : '';

        return [
            'slug' => [$required, 'max:255', 'unique:articles,slug,'.$id],
            'title' => 'required|max:255',
            'image' => 'nullable|max:255',
            'description' => 'nullable|max:65000',
            'content' => 'nullable|max:16000000',
            'meta_title' => 'nullable|max:255',
            'meta_desc' => 'nullable|max:255',
            'created_at' => 'nullable|date'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (! $this->hasMainLanguage()) {
            return;
        }

        $this->slugifyInput('slug', ['title']);

        $this->boolifyInput('visible');
    }
}
