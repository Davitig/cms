<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TranslationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('translations');

        if (! is_null($id)) {
            $id = '|unique:translations,name,' . $id;
        }

        return [
            'name'  => 'required|min:2|max:32|regex:/^\w+$/' . $id,
            'title' => 'required|min:2',
            'value' => 'required|min:2'
        ];
    }

    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return (array) trans('attributes');
    }
}