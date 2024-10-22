<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class PageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('page');

        return [
            'slug' => 'required|min:1|unique:pages,slug,'.$id,
            'title' => 'required|min:2',
            'short_title' => 'required|min:2',
            'type' => 'required',
            'type_id' => 'nullable|integer'
        ];
    }

    /**
     * Perform action before validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function beforeValidation(Validator $validator): void
    {
        $validator->sometimes('type_id', 'required', function ($input) {
            return in_array($input->type, cms_pages('listable'));
        });
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        if (! $this->filled('short_title')) {
            $input['short_title'] = $this->get('title');
        }

        $this->slugifyInput($input, 'slug', ['short_title']);

        $this->boolifyInput($input, ['visible']);

        return $input;
    }
}
