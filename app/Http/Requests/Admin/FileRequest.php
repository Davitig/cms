<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class FileRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:250',
            'file' => 'required'
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

        $this->boolifyInput('visible');
    }
}
