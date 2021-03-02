<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/upload', '\App\Http\Controllers\UploadController@index')->middleware(['auth'])->name('upload.index');
Route::get('/upload-chunk', '\App\Http\Controllers\UploadController@uploadChunk')->middleware(['auth'])->name('upload.upload_chunk');

require __DIR__.'/auth.php';
