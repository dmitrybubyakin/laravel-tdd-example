<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
            $temporaryUploadsCount = DB::table('temporary_uploads')->where('uuid', $this->uuid())->count();

            if ($temporaryUploadsCount >= $this->attachmentsLimit) {
                $fail('Attachments limit is reached.');
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
