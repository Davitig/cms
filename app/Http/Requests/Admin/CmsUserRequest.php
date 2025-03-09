<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Validation\Rules\File;

class CmsUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('cms_user');

        return [
            'email' => 'required|string|email|max:255|unique:cms_users,email,'.$id,
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'cms_user_role_id' => 'required|integer|exists:cms_user_roles,id',
            'photo' => ['nullable', File::image()->max(5 * 1024)],
            'password' => array_merge(
                $this->isMethod('POST') ? ['required'] : ['nullable'],
                ['min:8', 'confirmed']
            )
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->boolifyInput('blocked');

        $user = $this->user('cms');

        if ($user->id == $this->route('cms_user')) {
            $this->offsetSet('cms_user_role_id', $user->cms_user_role_id);
            $this->offsetSet('blocked', 0);
        }
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

        if (! $this->user('cms')->hasFullAccess()) {
            $this->offsetUnset('cms_user_role_id');
        }
    }
}
