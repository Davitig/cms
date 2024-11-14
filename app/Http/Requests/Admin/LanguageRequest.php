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
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        $input['language'] = strtolower($this->get('language'));

        return $input;
    }
}
