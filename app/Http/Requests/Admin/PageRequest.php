<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Validation\Rules\RequiredIf;

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

        $required = $this->isLanguageRelated() ? '' : 'required';

        return [
            'slug' => [$required, 'unique:pages,slug,'.$id],
            'title' => 'required',
            'short_title' => 'required',
            'type' => $required,
            'type_id' => new RequiredIf(fn () =>
                array_key_exists(
                    $this->get('type'), cms_pages('listable.collections')
                )
            )
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (! $this->filled('short_title')) {
            $this->offsetSet('short_title', $this->get('title'));
        }

        if ($this->isLanguageRelated()) {
            return;
        }

        $this->slugifyInput('slug', ['short_title']);

        $this->boolifyInput('visible');
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'type_id' => $this->get('type')
        ];
    }
}
