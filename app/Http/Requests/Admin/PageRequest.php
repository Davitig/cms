<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class PageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('page');

        return [
            'slug' => 'required|unique:pages,slug,'.$id,
            'title' => 'required',
            'short_title' => 'required',
            'type' => 'required',
            'type_id' => 'nullable|integer'
        ];
    }

    /**
     * Handle a before validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function beforeValidation(Validator $validator): void
    {
        $validator->sometimes('type_id', 'required', function ($input) {
            return array_key_exists($input->type, cms_pages('listable.collections'));
        });
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->filled('short_title')) {
            $this->offsetSet('short_title', $this->get('title'));
        }

        $this->slugifyInput('slug', ['short_title']);

        $this->boolifyInput('visible');
    }
}
