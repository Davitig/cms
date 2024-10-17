<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class FaqRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null)
    {
        $input = parent::all();

        $this->boolifyInput($input, ['visible']);

        return $input;
    }
}
