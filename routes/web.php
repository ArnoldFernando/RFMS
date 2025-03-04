<?php

use App\Models\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/files/download/{id}', function ($id) {
    $record = \App\Models\File::findOrFail($id);

    $filePath = asset('storage/files/' . $record->file); // Correct path

    if (!file_exists($filePath)) {
        abort(404, 'File not found.');
    }

    $fileExtension = pathinfo($record->file, PATHINFO_EXTENSION);
    $fileName = $record->file_name . '.' . $fileExtension; // Keep original extension

    return response()->download($filePath, $fileName);
})->name('files.download');
