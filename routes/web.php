<?php


use App\Models\File;
use Illuminate\Support\Facades\Response;
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


Route::get('/download/{id}', function ($id) {
    $record = File::findOrFail($id);

    $originalPath = public_path('storage/' . $record->file); // File location
    $fileExtension = pathinfo($originalPath, PATHINFO_EXTENSION); // Get file extension
    $newFileName = $record->file_name . '.' . $fileExtension; // Rename file

    if (file_exists($originalPath)) {
        return Response::download($originalPath, $newFileName);
    } else {
        abort(404, 'File not found.');
    }
})->name('file.download');
