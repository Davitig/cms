<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class VideoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:2|max:250',
            'file' => 'required'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        $this->boolifyInput($input, ['visible']);

        return $input;
    }
}
