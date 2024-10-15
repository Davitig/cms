<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CmsUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('cms_user');

        return [
            'email' => 'required|string|email|max:255|unique:cms_users,email,'.$id,
            'first_name' => 'required|min:2|max:35',
            'last_name' => 'required|min:2|max:35',
            'role' => 'required',
            'password' => array_merge(
                $this->isMethod('POST') ? ['required'] : ['nullable'],
                ['min:8', 'confirmed']
            )
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null)
    {
        $input = parent::all();

        $id = $this->route('cms_user');

        $user = $this->user('cms');

        $input['blocked'] = $this->filled('blocked') ? 1 : 0;

        if ($user->id == $id) {
            $input['role'] = $user->role;
            $input['blocked'] = 0;
        } elseif (! in_array($this->get('role'), array_keys(user_roles()))) {
            $input['role'] = null;
        }

        return $input;
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        if (! $this->filled('password')) {
            $this->offsetUnset('password');
            $this->offsetUnset('password_confirmation');
        }
    }
}
