<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function index()
    {
        return view('upload.index');
    }

    public function uploadChunk(Request $request)
    {
        $fileFormInput = $request->file('file');
        $fileName = $request->post('file_name');
        $uploadFinished = $request->post('upload_finished');
        $chunkCounter = $request->post('chunk_counter');
        $numberOfChunks = $request->post('number_of_chunks');
        $chunkSize = $request->post('chunk_size');
        $fileNameChunk = $fileName . '.chunk';

       // Open temp file
        $tempFileInstance = fopen($fileNameChunk, $chunkCounter == 1 ? 'wb' : 'ab');
        if ($tempFileInstance) {

            // Read binary input stream and append it to temp file
            $chunkFileInstance = fopen($fileFormInput->getRealPath(), 'rb');
            if ($chunkFileInstance) {
                while ($chunkbuffer = fread($chunkFileInstance, $chunkSize)) {
                    fwrite($tempFileInstance, $chunkbuffer);
                }
            }
            fclose($tempFileInstance);
        }

        if ($uploadFinished) {

            rename($fileNameChunk, $fileName);

            return ['status'=>true, 'uploaded'=>true, 'message'=>'File uploaded is finished.'];
        }

        return ['status'=>true];
    }
}
