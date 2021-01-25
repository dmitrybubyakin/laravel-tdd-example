<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ContactFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'g-recaptcha-response' => ['required', 'captcha'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function validated(): array
    {
        return Arr::except(parent::validated(), ['g-recaptcha-response']);
    }
}
