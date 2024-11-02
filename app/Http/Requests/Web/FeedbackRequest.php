<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class FeedbackRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|string|email',
            'text' => 'required',
            'captcha' => 'required|captcha'
        ];
    }
}
