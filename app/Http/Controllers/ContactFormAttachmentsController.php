<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;

class ContactFormAttachmentsController
{
    public function __invoke(string $uuid): JsonResponse
    {
        return response()->json(
            TemporaryUpload::for($uuid)->get(['hash', 'filename']),
        );
    }
}
