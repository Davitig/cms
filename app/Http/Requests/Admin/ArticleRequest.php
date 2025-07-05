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

        $required = language()->mainIsActive() ? 'required' : '';

        return [
            'slug' => [$required, 'unique:articles,slug,'.$id],
            'title' => 'required',
            'created_at' => [$required, 'date']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->isNotFilled('created_at')) {
            $this->offsetUnset('created_at');
        }

        if (! language()->mainIsActive()) {
            return;
        }

        $this->slugifyInput('slug', ['title']);

        $this->boolifyInput('visible');
    }
}
