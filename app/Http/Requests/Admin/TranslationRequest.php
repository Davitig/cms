<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TranslationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('translation') ?: $this->get('id');

        $required = $this->hasMainLanguage() ? 'required' : '';

        return [
            'code' => [$required, 'max:18', 'regex:/^[a-z_]+$/', 'unique:translations,code,' . $id],
            'value' => 'required'
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

        if (! $this->filled('code')) {
            $this->offsetSet('code', str($this->get('value'))->snake()->toString());
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'regex' => 'The :attribute field must only contain lowercase letters and underscores.'
        ];
    }
}
