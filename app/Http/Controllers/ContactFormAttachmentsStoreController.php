<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormAttachmentRequest;
use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;

class ContactFormAttachmentsStoreController
{
    public function __invoke(ContactFormAttachmentRequest $request): JsonResponse
    {
        $request->attachment()->store('', 'uploads');

        TemporaryUpload::create([
            'uuid' => $request->uuid(),
            'hash' => $request->attachment()->hashName(),
            'filename' => $request->attachment()->getClientOriginalName(),
        ]);

        return response()->json([
            'uuid' => $request->uuid(),
        ]);
    }
}
