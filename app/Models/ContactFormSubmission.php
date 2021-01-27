<?php

namespace App\Models;

use App\AnonymousNotifiableFactory;
use App\Notifications\Admin\NewContactFormSubmission;
use App\Notifications\FormWillBeProcessedSoon;
use App\Services\GoogleSpreadsheetApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ContactFormSubmission extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function moveTemporaryUploads(): void
    {
        TemporaryUpload::for($this->uuid)->get()->each(
            fn (TemporaryUpload $temporaryUpload) => $temporaryUpload->move()
        );
    }

    public function notifyAdmin(): void
    {
        AnonymousNotifiableFactory::email('admin@app.test')->notify(
            new NewContactFormSubmission($this)
        );
    }

    public function notifySubmitter(): void
    {
        $this->notify(new FormWillBeProcessedSoon);
    }

    public function submitFormDataToGoogleSpreadsheet(): void
    {
        app(GoogleSpreadsheetApp::class)->submitFormData($this->toArray());
    }
}
