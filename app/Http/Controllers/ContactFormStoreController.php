<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactFormSubmission;
use Illuminate\Http\JsonResponse;

class ContactFormStoreController
{
    public function __invoke(ContactFormRequest $request): JsonResponse
    {
        $form = ContactFormSubmission::create($request->validated());

        $form->moveTemporaryUploads();

        return response()->json();
    }
}
