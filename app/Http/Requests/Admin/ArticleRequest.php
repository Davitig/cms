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

        return [
            'slug' => 'required|unique:articles,slug,'.$id,
            'title' => 'required',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        $this->slugifyInput($input, 'slug', ['title']);

        $this->boolifyInput($input, ['visible']);

        if (! $this->filled('created_at')) {
            unset($input['created_at']);
        }

        return $input;
    }
}
