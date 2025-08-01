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
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'email' => 'required|string|email|max:255',
            'text' => 'required|max:5000',
            'captcha' => 'required|captcha|max:9'
        ];
    }
}
