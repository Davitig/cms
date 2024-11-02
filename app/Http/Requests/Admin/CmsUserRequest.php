<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\CmsUserRole;

class CmsUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route('cms_user');

        return [
            'email' => 'required|string|email|max:255|unique:cms_users,email,'.$id,
            'first_name' => 'required|min:2|max:35',
            'last_name' => 'required|min:2|max:35',
            'cms_user_role_id' => 'required|integer',
            'password' => array_merge(
                $this->isMethod('POST') ? ['required'] : ['nullable'],
                ['min:8', 'confirmed']
            )
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function all($keys = null): array
    {
        $input = parent::all();

        $id = $this->route('cms_user');

        $user = $this->user('cms');

        $input['blocked'] = $this->filled('blocked') ? 1 : 0;

        $roleIds = (new CmsUserRole)->pluck('id')->toArray();

        if ($user->id == $id) {
            $input['cms_user_role_id'] = $user->cms_user_role_id;
            $input['full_access'] = $user->full_access;
            $input['blocked'] = 0;
        } elseif (! $user->hasFullAccess() || ! in_array($this->get('cms_user_role_id'), $roleIds)) {
            $input['cms_user_role_id'] = null;
            $input['full_access'] = 0;
        }

        return $input;
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        if (! $this->filled('password')) {
            $this->offsetUnset('password');
            $this->offsetUnset('password_confirmation');
        }
    }
}
