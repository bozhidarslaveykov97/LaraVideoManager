<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;

class UploadController extends Controller
{

    public $storagePath = false;
    public $storageRealPath = false;

    public function __construct()
    {
        $this->storagePath = 'app'.DIRECTORY_SEPARATOR.'public';
        $this->storageRealPath = storage_path($this->storagePath);
    }

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

        $fileNameChunk = $this->storageRealPath . DIRECTORY_SEPARATOR . $fileName . '.chunk';

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

            $newFileRealPath = $this->storageRealPath . DIRECTORY_SEPARATOR . $fileName;
            $filePath = $this->storagePath . DIRECTORY_SEPARATOR . $fileName;

            rename($fileNameChunk, $newFileRealPath);

            $findFile = File::where('file_path', $filePath)->first();
            if ($findFile == null) {
                $findFile = new File();
            }
            $findFile->name = $fileName;
            $findFile->file_size = filesize($newFileRealPath);
            $findFile->file_name = $fileName;
            $findFile->file_path = $filePath;
            $findFile->storage_path = $this->storagePath;
            $findFile->save();

            $this->_generateVideoThumbnails($findFile->id);

            $fileUrl = Storage::url($fileName);

            $findVideo = Video::where('file_id', $findFile->id)->first();
            if ($findVideo == null) {
                $findVideo = new Video();
                $findVideo->file_id = $findFile->id;
                $findVideo->name = $findFile->name;
            }
            $findVideo->save();

            return ['status' => true, 'uploaded' => true, 'message' => 'File uploaded is finished.', 'file_url'=>$fileUrl];
        }

        return ['status' => true];
    }

    private function _generateVideoThumbnails(int $id)
    {
        $findFile = File::where('id', $id)->first();
        if ($findFile == null) {
            return false;
        }

        $videoRealPath = $this->storageRealPath . DIRECTORY_SEPARATOR . $findFile->file_name;

        try {
            $thumbnailFileName =  'video-thumbnail-' . $findFile->id . '.jpg';
            $thumbnailGifFileName =  'video-thumbnail-animated-' . $findFile->id . '.gif';

            $thumbnailRealFilePath = $this->storageRealPath . DIRECTORY_SEPARATOR . $thumbnailFileName;
            $thumbnailFilePath = $this->storagePath . DIRECTORY_SEPARATOR . $thumbnailFileName;

            $thumbnailGifRealFilePath = $this->storageRealPath . DIRECTORY_SEPARATOR . $thumbnailGifFileName;
            $thumbnailGifFilePath = $this->storagePath . DIRECTORY_SEPARATOR . $thumbnailGifFileName;

            $ffmpeg = FFMpeg::create();
            $videoOpen = $ffmpeg->open($videoRealPath);
            $videoOpen->frame(TimeCode::fromSeconds(3))->save($thumbnailRealFilePath);
            $videoOpen->gif(TimeCode::fromSeconds(3), new Dimension(640, 480), 5)->save($thumbnailGifRealFilePath);

            if (is_file($thumbnailGifRealFilePath)) {
                $findFile->thumbnail_gif_name = $thumbnailGifFileName;
                $findFile->thumbnail_gif_path = $thumbnailGifFilePath;
            }

            // dd($videoOpen->getFormat());

            if (is_file($thumbnailRealFilePath)) {
                $findFile->thumbnail_name = $thumbnailFileName;
                $findFile->thumbnail_path = $thumbnailFilePath;
            }

            $findFile->save();

        } catch (\Exception $e) {

        }
    }
}
