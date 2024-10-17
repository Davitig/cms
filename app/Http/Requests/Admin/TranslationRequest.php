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
    public function rules()
    {
        $id = $this->route('translation') ?: $this->get('id');

        return [
            'code' => 'required|min:2|max:18|regex:/^\w+$/|unique:translations,code,' . $id,
            'title' => 'required|min:2',
            'value' => 'required'
        ];
    }
}
