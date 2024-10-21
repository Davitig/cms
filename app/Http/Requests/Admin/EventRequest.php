<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class EventRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $id = $this->route('event');

        return [
            'slug' => 'required|min:1|unique:events,slug,'.$id,
            'title' => 'required|min:2',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null)
    {
        $input = parent::all();

        $this->slugifyInput($input, 'slug', ['title']);

        $this->boolifyInput($input, ['visible']);

        return $input;
    }
}
