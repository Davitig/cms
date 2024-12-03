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

        $orderList = array_keys(deep_collection('galleries.order_by'));

        $sortList = array_keys(deep_collection('galleries.sort'));

        $typeRule = $this->method() == 'POST'
            ? ['type' => ['required', Rule::in(
                array_keys(deep_collection('galleries.types'))
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
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        if ($this->method() != 'POST') {
            unset($input['type']);
        }

        return $input;
    }
}
