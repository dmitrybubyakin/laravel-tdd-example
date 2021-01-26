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
            'uuid' => ['required', 'uuid', 'unique:contact_form_submissions'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function uuid(): string
    {
        return $this->input('uuid');
    }

    public function validated(): array
    {
        return Arr::except(parent::validated(), ['g-recaptcha-response']);
    }

    public function messages(): array
    {
        return [
            'uuid.unique' => 'The form has already been submitted.',
        ];
    }
}
