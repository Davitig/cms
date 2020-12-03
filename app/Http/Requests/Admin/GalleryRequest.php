<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class GalleryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('gallery');

        return [
            'slug' => 'required|min:2|unique:galleries,slug,'.$id,
            'title' => 'required|min:2',
            'type' => 'required',
            'admin_order_by' => 'required',
            'admin_sort' => 'required',
            'admin_per_page' => 'required|numeric|min:1|max:50',
            'web_order_by' => 'required',
            'web_sort' => 'required',
            'web_per_page' => 'required|numeric|min:1|max:50'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function all($keys = null)
    {
        $input = parent::all();

        $this->slugifyInput($input, 'slug', ['title']);

        if (! array_key_exists($this->get('type'), deep_collection('galleries.types'))) {
            $input['type'] = null;
        }

        $orderList = deep_collection('galleries.order_by');

        if (! array_key_exists($this->get('admin_order_by'), $orderList)) {
            $input['admin_order_by'] = null;
        }

        if (! array_key_exists($this->get('web_order_by'), $orderList)) {
            $input['web_order_by'] = null;
        }

        $sortList = deep_collection('galleries.sort');

        if (! array_key_exists($this->get('admin_sort'), $sortList)) {
            $input['admin_sort'] = null;
        }

        if (! array_key_exists($this->get('web_sort'), $sortList)) {
            $input['web_sort'] = null;
        }

        $this->boolifyInput($input, ['visible']);

        return $input;
    }
}
