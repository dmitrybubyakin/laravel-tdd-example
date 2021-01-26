<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactFormSubmission;
use Illuminate\Http\JsonResponse;

class ContactFormStoreController
{
    public function __invoke(ContactFormRequest $request): JsonResponse
    {
        /** @var ContactFormSubmission */
        $form = ContactFormSubmission::create($request->validated());
        $form->moveTemporaryUploads();
        $form->notifyAdmin();
        $form->notifySubmitter();

        return response()->json();
    }
}
