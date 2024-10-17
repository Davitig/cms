<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class FeedbackRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|string|email',
            'text' => 'required',
            'captcha' => 'required|captcha'
        ];
    }
}
