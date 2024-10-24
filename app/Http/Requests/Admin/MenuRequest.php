<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class MenuRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:2|max:250'
        ];
    }
}
