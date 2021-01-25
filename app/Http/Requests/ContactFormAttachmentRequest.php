<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContactFormAttachmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'g-recaptcha-response' => ['required', 'captcha'],
            'uuid' => ['required', 'uuid'],
            'attachment' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ];
    }

    public function uuid(): string
    {
        return $this->input('uuid');
    }

    public function attachment(): UploadedFile
    {
        return $this->file('attachment');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'uuid' => $this->input('uuid', (string) Str::uuid()),
        ]);
    }
}
