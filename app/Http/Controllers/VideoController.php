<?php

namespace App\Http\Controllers;

use App\LaraVideoStream;
use App\Models\Video;
use App\VideoStream;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::paginate();

        return view('video.index', compact('videos'));
    }

    public function show(int $id)
    {
        $video = Video::where('id', $id)->first();

        return view('video.show', compact('video'));
    }

    public function stream(int $id)
    {
        $video = Video::where('id', $id)->first();
        $path = storage_path($video->file()->file_path);

        $stream = new LaraVideoStream($path);
        $stream->start();

    }

    public function delete(int $id) {

        $video = Video::where('id', $id)->first();
        if ($video) {
            $video->file()->delete();
            $video->delete();
        }

        return redirect(route('video.index'));
    }

    public function download(int $id) {

    }
}
