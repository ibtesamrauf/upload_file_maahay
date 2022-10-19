<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadFileController;
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
    return redirect()->route('login');
});

Auth::routes();

Route::get('/upload/file', [UploadFileController::class, 'uploadFile'])->name('upload.file');
Route::post('/file/upload/store', [UploadFileController::class, 'fileStore'])->name('file.upload.store');

Route::post('/user/share/image/create', [UploadFileController::class, 'userShareImageCreate'])->name('user.share.image.create');
Route::get('/user/share/files', [UploadFileController::class, 'userShareFiles'])->name('user.share.files');