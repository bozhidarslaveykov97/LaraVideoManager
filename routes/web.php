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

Route::resource('video', '\App\Http\Controllers\VideoController')->middleware(['auth']);
Route::get('video/stream/{id}', '\App\Http\Controllers\VideoController@stream')->middleware(['auth'])->name('video.stream');

Route::get('/upload', '\App\Http\Controllers\UploadController@index')->middleware(['auth'])->name('upload.index');
Route::post('/upload-chunk', '\App\Http\Controllers\UploadController@uploadChunk')
    ->middleware(['auth'])
    ->name('upload.upload_chunk');

require __DIR__.'/auth.php';
