<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactFormSubmission;
use Illuminate\Http\JsonResponse;

class ContactFormStoreController
{
    public function __invoke(ContactFormRequest $request): JsonResponse
    {
        ContactFormSubmission::create($request->validated());

        return response()->json();
    }
}
