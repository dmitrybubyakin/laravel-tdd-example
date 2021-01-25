<?php

namespace App\Http\Requests;

use App\Models\ContactFormSubmission;
use App\Models\TemporaryUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContactFormAttachmentRequest extends FormRequest
{
    private int $attachmentsLimit = 3;

    public function rules(): array
    {
        return [
            'g-recaptcha-response' => ['required', 'captcha'],
            'uuid' => ['required', 'uuid'],
            'attachment' => [
                $this->validateAttachmentsLimit(),
                $this->validateFormHaventBeenSubmitted(),
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],
        ];
    }

    private function validateAttachmentsLimit(): callable
    {
        return function ($attribute, $value, callable $fail): void {
            if (TemporaryUpload::for($this->uuid())->count() >= $this->attachmentsLimit) {
                $fail('Attachments limit is reached.');
            }
        };
    }

    private function validateFormHaventBeenSubmitted(): callable
    {
        return function ($attribute, $value, callable $fail): void {
            if (ContactFormSubmission::where('uuid', $this->uuid())->exists()) {
                $fail('The attachment cannot be uploaded. The form has already been submitted.');
            }
        };
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
