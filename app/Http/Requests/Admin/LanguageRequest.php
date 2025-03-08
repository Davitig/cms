<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class LanguageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('language');

        return [
            'language' => 'required|size:2|unique:languages,language,'.$id,
            'short_name' => 'required|min:2|max:3',
            'full_name' => 'required|min:2|max:32'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->offsetSet('language', strtolower($this->get('language')));

        $this->boolifyInput('visible', 'main');
    }
}
