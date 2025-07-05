<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CollectionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $orderList = array_keys((array) cms_config('listable.collections.order_by'));

        $sortList = array_keys((array) cms_config('listable.collections.sort'));

        if ($this->isMethod(self::METHOD_POST)) {
            $typeRule['type'] = ['required', Rule::in(
                array_keys((array) cms_config('listable.collections.types'))
            )];
        } else {
            $typeRule = [];
        }

        return $typeRule + [
                'title' => 'required',
                'admin_order_by' => ['required', Rule::in($orderList)],
                'admin_sort' => ['required', Rule::in($sortList)],
                'admin_per_page' => 'required|numeric|max:200',
                'web_order_by' => ['required', Rule::in($orderList)],
                'web_sort' => ['required', Rule::in($sortList)],
                'web_per_page' => 'required|numeric|max:200'
            ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->isMethod(self::METHOD_POST)) {
            $this->offsetUnset('type');
        }
    }
}
