<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ContactFormAttachmentsDeleteController
{
    public function __invoke(TemporaryUpload $temporaryUpload): JsonResponse
    {
        Storage::disk('uploads')->delete($temporaryUpload->hash);

        $temporaryUpload->delete();

        return response()->json();
    }
}
