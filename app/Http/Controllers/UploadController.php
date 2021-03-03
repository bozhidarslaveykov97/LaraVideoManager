<?php

namespace App\Http\Controllers;

use App\Models\File;
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

        $storagePath = 'app'.DIRECTORY_SEPARATOR.'public';
        $storageRealPath = storage_path($storagePath);
        $fileNameChunk = $storageRealPath . DIRECTORY_SEPARATOR . $fileName . '.chunk';

        // Open temp file
        $tempFileInstance = fopen($fileNameChunk, $chunkCounter == 1 ? 'wb' : 'ab'); // Write if is first chunk, append if is next chunk
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

            $newFileRealPath = $storageRealPath . DIRECTORY_SEPARATOR . $fileName;
            $filePath = $storagePath . DIRECTORY_SEPARATOR . $fileName;

            rename($fileNameChunk, $newFileRealPath);

            $findFile = File::where('file_path', $filePath)->first();
            if ($findFile == null) {
                $findFile = new File();
            }
            $findFile->name = $fileName;
            $findFile->file_path = $filePath;
            $findFile->storage_path = $storagePath;
            $findFile->save();

            $fileUrl = Storage::url($fileName);

            return ['status' => true, 'uploaded' => true, 'message' => 'File uploaded is finished.', 'file_url'=>$fileUrl];
        }

        return ['status' => true];
    }
}
