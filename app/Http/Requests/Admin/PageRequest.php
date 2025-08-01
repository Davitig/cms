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

        $required = $this->hasMainLanguage() ? 'required' : '';

        return [
            'slug' => [$required, 'max:255', 'unique:pages,slug,'.$id],
            'title' => 'required|max:255',
            'short_title' => 'required|max:255',
            'type' => [$required, 'max:64'],
            'type_id' => new RequiredIf(fn () =>
                array_key_exists(
                    $this->get('type'), cms_pages('listable.collections')
                )
            ),
            'image' => 'nullable|max:255',
            'description' => 'nullable|max:65000',
            'content' => 'nullable|max:16000000',
            'meta_title' => 'nullable|max:255',
            'meta_desc' => 'nullable|max:255'
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

        if (! $this->hasMainLanguage()) {
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
