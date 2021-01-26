<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactFormSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function moveTemporaryUploads(): void
    {
        TemporaryUpload::for($this->uuid)->get()->each(
            fn (TemporaryUpload $temporaryUpload) => $temporaryUpload->move()
        );
    }
}
