<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ProductRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product');

        return [
            'slug' => 'required|unique:products,slug,'.$id,
            'title' => 'required',
            'price' => 'required|numeric|between:0,999999.99',
            'quantity' => 'required|integer'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->slugifyInput('slug', ['title']);

        $this->boolifyInput('visible');
    }
}
