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

        return [
            'code' => 'required|max:18|regex:/^\w+$/|unique:translations,code,' . $id,
            'title' => 'required',
            'value' => 'required'
        ];
    }
}
