<?php


use FileManager\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('file-manager')->group(function () {
    Route::get('/ping', function () {
        return response()->json(['status' => 'file-manager ready']);
    });
});


Route::post('/filemanager/upload', [FileUploadController::class, 'store']);
