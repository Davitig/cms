<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GalleryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('gallery');

        $orderList = array_keys((array) cms_config('galleries.order_by'));

        $sortList = array_keys((array) cms_config('galleries.sort'));

        $typeRule = $this->isMethod($this::METHOD_POST)
            ? ['type' => ['required', Rule::in(
                array_keys((array) cms_config('galleries.types'))
            )]] : [];

        return $typeRule + [
            'slug' => 'required|unique:galleries,slug,'.$id,
            'title' => 'required',
            'admin_order_by' => ['required', Rule::in($orderList)],
            'admin_sort' => ['required', Rule::in($sortList)],
            'admin_per_page' => 'required|numeric|max:50',
            'web_order_by' => ['required', Rule::in($orderList)],
            'web_sort' => ['required', Rule::in($sortList)],
            'web_per_page' => 'required|numeric|max:50'
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

        if (! $this->isMethod($this::METHOD_POST)) {
            $this->offsetUnset('type');
        }
    }
}
