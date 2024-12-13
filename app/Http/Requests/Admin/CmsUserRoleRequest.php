<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CmsUserRoleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => 'required',
            'full_access' => 'integer|in:0,1'
        ];
    }
}
