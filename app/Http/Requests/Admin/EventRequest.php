<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class EventRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('event');

        $required = language()->mainIsActive() ? 'required' : '';

        return [
            'slug' => [$required, 'unique:events,slug,'.$id],
            'title' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (! language()->mainIsActive()) {
            return;
        }

        $this->slugifyInput('slug', ['title']);

        $this->boolifyInput('visible');
    }
}
