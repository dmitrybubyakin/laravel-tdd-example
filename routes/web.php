<?php

use App\Http\Controllers\ContactFormAttachmentsController;
use App\Http\Controllers\ContactFormAttachmentsDeleteController;
use App\Http\Controllers\ContactFormAttachmentsStoreController;
use App\Http\Controllers\ContactFormStoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('forms/contact', ContactFormStoreController::class);
Route::post('forms/contact/attachments', ContactFormAttachmentsStoreController::class);
Route::delete('forms/contact/attachments/{temporaryUpload:hash}', ContactFormAttachmentsDeleteController::class);
Route::get('forms/contact/{uuid}/attachments', ContactFormAttachmentsController::class);
